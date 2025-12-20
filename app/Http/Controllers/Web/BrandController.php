<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandImage;
use App\Models\Products;
use App\Models\Category;
use Illuminate\Http\Request;

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

        // Lấy danh sách sản phẩm theo thương hiệu
        $sort = $request->get('sort', 'newest');
        $categoryId = $request->get('category');
        $materialId = $request->get('material_id');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        $colorId = $request->get('color_id');

        $hasJoin = false;
        $products = Products::where('products.brand_id', $brand->id)
            ->where('products.hidden', 1)
            ->select('products.*');

        // Filter by category
        if ($categoryId) {
            $products->join('product_categories', 'product_categories.productID', '=', 'products.id')
                ->where('product_categories.CategoryID', $categoryId);
            $hasJoin = true;
        }

        // Filter by material
        if ($materialId) {
            $products->where('products.material_id', $materialId);
        }

        // Apply price filters
        if (!empty($priceMin) || !empty($priceMax)) {
            $priceMin = $priceMin ?? 0;
            $priceMax = $priceMax ?? 999999999;
            $products->where(function($query) use ($priceMin, $priceMax) {
                $query->whereBetween('products.price_sale', [$priceMin, $priceMax])
                    ->orWhereBetween('products.price', [$priceMin, $priceMax]);
            });
        }

        // Filter by color
        if (!empty($colorId)) {
            if ($hasJoin) {
                $products->join('product_color', 'product_color.productID', '=', 'products.id')
                    ->where('product_color.colorID', $colorId);
            } else {
                $products->join('product_color', 'product_color.productID', '=', 'products.id')
                    ->where('product_color.colorID', $colorId);
            }
            $hasJoin = true;
        }

        // Add distinct if we have joins
        if ($hasJoin) {
            $products->distinct();
        }

        // Apply sorting
        switch($sort) {
            case 'price_asc':
                $products->orderBy('products.price_sale', 'ASC')->orderBy('products.price', 'ASC');
                break;
            case 'price_desc':
                $products->orderBy('products.price_sale', 'DESC')->orderBy('products.price', 'DESC');
                break;
            case 'name_asc':
                $products->orderBy('products.name', 'ASC');
                break;
            case 'name_desc':
                $products->orderBy('products.name', 'DESC');
                break;
            case 'newest':
            default:
                $products->orderBy('products.id', 'DESC')->orderBy('products.weight', 'ASC');
                break;
        }

        $products = $products->paginate(12);

        // Lấy categories liên quan đến brand
        $categories = Brand::getCategoryByBrandIDProduct($brand->id);
        
        // Lấy materials liên quan
        $materials = Brand::getMarterialByBrandIDProduct($brand->id);

        // Lấy hình ảnh từ bảng brand_images
        $brandImages = BrandImage::getBrandImageByID($brand->id);

        return view('web.page.brand.detail', [
            'title' => $brand->name . ' - Thương Hiệu - Mắt Kính Sài Gòn',
            'brand' => $brand,
            'products' => $products,
            'categories' => $categories,
            'materials' => $materials,
            'brandImages' => $brandImages,
        ]);
    }
}

