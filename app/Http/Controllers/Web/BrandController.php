<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandImage;
use App\Models\ProductImage;
use App\Models\Products;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    /**
     * Hiển thị danh sách tất cả thương hiệu
     */
    public function index(Request $request)
    {
        $brands = Brand::getAllBrand(28);
        
        return view('web.page.brand.index', [
            'title' => 'Thương Hiệu - Mắt Kính Sài Gòn',
            'brands' => $brands,
        ]);
    }

    /**
     * Hiển thị chi tiết thương hiệu
     */
    public function detail($alias, Request $request)
    {
        $brand = Brand::getDetailBrandByAlias($alias);
        
        if (!$brand || $brand->hidden != Brand::IS_ACTIVE) {
            abort(404);
        }

        // Lấy hình ảnh từ bảng brand_images
        $brandImages = BrandImage::getBrandImageByID($brand->id);

        // Lấy sản phẩm thuộc thương hiệu
        $selectedMaterialId = $request->get('brand_material_id');
        $brandProductsQuery = Products::select('products.*')
            ->where('products.brand_id', $brand->id)
            ->where('products.hidden', 1);

        if (!empty($selectedMaterialId) && $selectedMaterialId !== 'all') {
            $brandProductsQuery->where('products.material_id', $selectedMaterialId);
        }

        $brandProducts = $brandProductsQuery
            ->orderBy('products.id', 'DESC')
            ->orderBy('products.weight', 'ASC')
            ->paginate(12)
            ->withQueryString();

        $productIds = $brandProducts->pluck('id')->all();

        // Pre-process product data để giảm logic trong view
        $processedBrandProducts = collect();
        if (!empty($productIds)) {
            $allImages = ProductImage::whereIn('product_id', $productIds)
                ->orderBy('product_id')
                ->orderBy('weight', 'ASC')
                ->get()
                ->groupBy('product_id');

            $processedBrandProducts = $brandProducts->getCollection()->map(function ($product) use ($allImages) {
                $images = $allImages[$product->id] ?? collect();
                $mainImage = $images->count() > 0
                    ? asset('img/product/' . $images->first()->image)
                    : asset('img/product/no-image.jpg');
                $hoverImage = $images->count() > 1
                    ? asset('img/product/' . $images->get(1)->image)
                    : $mainImage;

                $priceSale = $product->price_sale ?? $product->price ?? 0;
                $price = $product->price ?? 0;
                $discount = $price > 0 && $priceSale < $price
                    ? round((($price - $priceSale) / $price) * 100)
                    : 0;

                return [
                    'product' => $product,
                    'mainImage' => $mainImage,
                    'hoverImage' => $hoverImage,
                    'priceSale' => $priceSale,
                    'price' => $price,
                    'discount' => $discount,
                ];
            });
        }

        // Lấy chất liệu thuộc thương hiệu theo sản phẩm
        $brandMaterials = Material::select('material.*')
            ->join('products', 'products.material_id', '=', 'material.id')
            ->where('products.brand_id', $brand->id)
            ->where('products.hidden', 1)
            ->distinct()
            ->orderBy('material.weight', 'ASC')
            ->orderBy('material.name', 'ASC')
            ->get();

        $brandMaterialCounts = Products::select('products.material_id', DB::raw('COUNT(DISTINCT products.id) as total'))
            ->join('material', 'material.id', '=', 'products.material_id')
            ->where('products.brand_id', $brand->id)
            ->where('products.hidden', 1)
            ->groupBy('products.material_id')
            ->pluck('total', 'products.material_id');

        return view('web.page.brand.detail', [
            'title' => $brand->name . ' - Thương Hiệu - Mắt Kính Sài Gòn',
            'brand' => $brand,
            'brandImages' => $brandImages,
            'brandProducts' => $brandProducts,
            'processedBrandProducts' => $processedBrandProducts,
            'brandMaterials' => $brandMaterials,
            'brandMaterialCounts' => $brandMaterialCounts,
            'selectedMaterialId' => $selectedMaterialId,
        ]);
    }
}

