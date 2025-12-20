<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Products;
use App\Models\News;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Cache;

class IndexController extends Controller
{
    /**
     * Trang chủ
     */
    public function index()
    {
        // Cache banners - 1 giờ
        try {
            $banners = Cache::remember('home_banners', 3600, function () {
                return Slider::where('hidden', 1)->orderBy('weight', 'asc')->get();
            });
        } catch (\Exception $e) {
            $banners = Slider::where('hidden', 1)->orderBy('weight', 'asc')->get();
        }

        // Cache categories - 1 giờ
        try {
            $categoriesProduct = Cache::remember('home_categories_product', 3600, function () {
                return Category::where('parent_id', 0)
                    ->where('type', 'product')
                    ->where('hidden', 1)
                    ->orderBy('weight', 'asc')
                    ->get();
            });
        } catch (\Exception $e) {
            $categoriesProduct = Category::where('parent_id', 0)
                ->where('type', 'product')
                ->where('hidden', 1)
                ->orderBy('weight', 'asc')
                ->get();
        }

        // Lấy sản phẩm theo từng category với cache và pre-process images
        $productsByCategory = [];
        $productIds = [];
        
        foreach ($categoriesProduct as $category) {
            $cacheKey = "home_products_category_{$category->id}";
            try {
                $products = Cache::remember($cacheKey, 1800, function () use ($category) {
                    return Products::getAllProductByParentID($category->id);
                });
            } catch (\Exception $e) {
                $products = Products::getAllProductByParentID($category->id);
            }
            
            $productsByCategory[$category->alias] = $products;
            // Collect product IDs để eager load images
            foreach ($products as $product) {
                $productIds[] = $product->id;
            }
        }

        // Eager load tất cả images một lần để tránh N+1 queries
        $productImagesMap = [];
        if (!empty($productIds)) {
            $allImages = ProductImage::whereIn('product_id', $productIds)
                ->orderBy('product_id')
                ->orderBy('weight', 'ASC')
                ->get()
                ->groupBy('product_id');
            
            foreach ($allImages as $productId => $images) {
                $productImagesMap[$productId] = $images->take(2); // Chỉ lấy 2 ảnh đầu
            }
        }

        // Pre-process product data để giảm logic trong view
        $processedProductsByCategory = [];
        foreach ($productsByCategory as $alias => $products) {
            $processedProductsByCategory[$alias] = $products->map(function ($product) use ($productImagesMap) {
                $images = $productImagesMap[$product->id] ?? collect();
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

        // Cache news - 30 phút
        try {
            $news = Cache::remember('home_news', 1800, function () {
                return News::where('hidden', 1)
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();
            });
        } catch (\Exception $e) {
            $news = News::where('hidden', 1)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }

        // Pre-process news data
        $processedNews = $news->map(function ($item) {
            return [
                'news' => $item,
                'imageUrl' => method_exists($item, 'getImage') ? $item->getImage() : '',
                'date' => $item->created_at ? $item->created_at->format('d/m/Y') : '',
                'detailUrl' => $item->alias ? route('new.detail', ['alias' => $item->alias]) : '#',
            ];
        });

        // Cache brands - 1 giờ
        try {
            $brands = Cache::remember('home_brands', 3600, function () {
                return Brand::where('hidden', 1)->orderBy('weight', 'asc')->get();
            });
        } catch (\Exception $e) {
            $brands = Brand::where('hidden', 1)->orderBy('weight', 'asc')->get();
        }

        return view('web.page.home.index', [
            'banners' => $banners,
            'categoriesProduct' => $categoriesProduct,
            'productsByCategory' => $productsByCategory, // Giữ lại để backward compatibility
            'processedProductsByCategory' => $processedProductsByCategory,
            'news' => $news, // Giữ lại để backward compatibility
            'processedNews' => $processedNews,
            'brands' => $brands,
        ]);
    }
}

