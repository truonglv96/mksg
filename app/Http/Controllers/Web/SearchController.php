<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\News;
use App\Models\Brand;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    /**
     * Tìm kiếm sản phẩm và tin tức
     */
    public function search(Request $request)
    {
        $keyword = trim($request->input('keyword', ''));
        $type = $request->input('type', 'all'); // all, product, news, brand
        
        if (empty($keyword)) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng nhập từ khóa tìm kiếm',
            ]);
        }

        $results = [
            'products' => collect(),
            'news' => collect(),
            'brands' => collect(),
            'partners' => collect(),
        ];

        // Tìm kiếm sản phẩm
        if ($type === 'all' || $type === 'product') {
            try {
                $cacheKey = "search_products_" . md5($keyword);
                $results['products'] = Cache::remember($cacheKey, 300, function () use ($keyword) {
                    $like = '%' . $keyword . '%';
                    return Products::where('hidden', 1)
                        ->where(function ($query) use ($like) {
                            $query->where('name', 'like', $like)
                                  ->orWhere('description', 'like', $like);
                        })
                        ->orderBy('id', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($product) {
                            $imageUrl = asset('img/product/no-image.jpg');
                            try {
                                $images = \App\Models\ProductImage::where('product_id', $product->id)
                                    ->orderBy('weight', 'ASC')
                                    ->limit(1)
                                    ->first();
                                if ($images) {
                                    $imageUrl = asset('img/product/' . $images->image);
                                }
                            } catch (\Exception $e) {
                                // Fallback to default image
                            }
                            
                            $priceSale = $product->price_sale ?? $product->price ?? 0;
                            $price = $product->price ?? 0;
                            
                            return [
                                'id' => $product->id,
                                'name' => $product->name,
                                'alias' => $product->alias,
                                'image' => $imageUrl,
                                'price' => $price,
                                'priceSale' => $priceSale,
                                'url' => $product->alias ? route('product.detail', [
                                    'categoryPath' => $product->getCategoryPath(),
                                    'productAlias' => $product->alias
                                ]) : '#',
                            ];
                        });
                });
            } catch (\Exception $e) {
                // Fallback nếu cache lỗi - tìm kiếm trực tiếp
                try {
                    $like = '%' . $keyword . '%';
                    $results['products'] = Products::where('hidden', 1)
                        ->where(function ($query) use ($like) {
                            $query->where('name', 'like', $like)
                                  ->orWhere('description', 'like', $like);
                        })
                        ->orderBy('id', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($product) {
                            $imageUrl = asset('img/product/no-image.jpg');
                            try {
                                $images = \App\Models\ProductImage::where('product_id', $product->id)
                                    ->orderBy('weight', 'ASC')
                                    ->limit(1)
                                    ->first();
                                if ($images) {
                                    $imageUrl = asset('img/product/' . $images->image);
                                }
                            } catch (\Exception $e) {
                                // Fallback to default image
                            }
                            
                            $priceSale = $product->price_sale ?? $product->price ?? 0;
                            $price = $product->price ?? 0;
                            
                            return [
                                'id' => $product->id,
                                'name' => $product->name,
                                'alias' => $product->alias,
                                'image' => $imageUrl,
                                'price' => $price,
                                'priceSale' => $priceSale,
                                'url' => $product->alias ? route('product.detail', [
                                    'categoryPath' => $product->getCategoryPath(),
                                    'productAlias' => $product->alias
                                ]) : '#',
                            ];
                        });
                } catch (\Exception $e2) {
                    $results['products'] = collect();
                }
            }
        }

        // Tìm kiếm tin tức
        if ($type === 'all' || $type === 'news') {
            try {
                $cacheKey = "search_news_" . md5($keyword);
                $results['news'] = Cache::remember($cacheKey, 300, function () use ($keyword) {
                    $like = '%' . $keyword . '%';
                    return News::where('hidden', 1)
                        ->where(function ($query) use ($like) {
                            $query->where('name', 'like', $like)
                                  ->orWhere('title', 'like', $like)
                                  ->orWhere('description', 'like', $like)
                                  ->orWhere('content', 'like', $like);
                        })
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get()
                        ->map(function ($item) {
                            $imageUrl = '';
                            try {
                                if (method_exists($item, 'getImage')) {
                                    $imageUrl = $item->getImage();
                                }
                            } catch (\Exception $e) {
                                // Fallback
                            }
                            
                            return [
                                'id' => $item->id,
                                'title' => $item->title ?? $item->name,
                                'alias' => $item->alias,
                                'image' => $imageUrl,
                                'description' => \Illuminate\Support\Str::limit(strip_tags($item->description ?? $item->content ?? ''), 100),
                                'date' => $item->created_at ? $item->created_at->format('d/m/Y') : '',
                                'url' => $item->alias ? route('new.detail', ['alias' => $item->alias]) : '#',
                            ];
                        });
                });
            } catch (\Exception $e) {
                // Fallback nếu cache lỗi - tìm kiếm trực tiếp
                try {
                    $like = '%' . $keyword . '%';
                    $results['news'] = News::where('hidden', 1)
                        ->where(function ($query) use ($like) {
                            $query->where('name', 'like', $like)
                                  ->orWhere('title', 'like', $like)
                                  ->orWhere('description', 'like', $like)
                                  ->orWhere('content', 'like', $like);
                        })
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get()
                        ->map(function ($item) {
                            $imageUrl = '';
                            try {
                                if (method_exists($item, 'getImage')) {
                                    $imageUrl = $item->getImage();
                                }
                            } catch (\Exception $e) {
                                // Fallback
                            }
                            
                            return [
                                'id' => $item->id,
                                'title' => $item->title ?? $item->name,
                                'alias' => $item->alias,
                                'image' => $imageUrl,
                                'description' => \Illuminate\Support\Str::limit(strip_tags($item->description ?? $item->content ?? ''), 100),
                                'date' => $item->created_at ? $item->created_at->format('d/m/Y') : '',
                                'url' => $item->alias ? route('new.detail', ['alias' => $item->alias]) : '#',
                            ];
                        });
                } catch (\Exception $e2) {
                    $results['news'] = collect();
                }
            }
        }

        // Tìm kiếm thương hiệu
        if ($type === 'all' || $type === 'brand') {
            try {
                $cacheKey = "search_brands_" . md5($keyword);
                $results['brands'] = Cache::remember($cacheKey, 300, function () use ($keyword) {
                    $like = '%' . $keyword . '%';
                    return Brand::where('hidden', 1)
                        ->where('name', 'like', $like)
                        ->orderBy('weight', 'asc')
                        ->orderBy('id', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($brand) {
                            $logoUrl = '';
                            try {
                                if (method_exists($brand, 'getLogoBrand')) {
                                    $logoUrl = $brand->getLogoBrand();
                                } elseif (!empty($brand->logo)) {
                                    $logoUrl = asset('img/brand/' . $brand->logo);
                                }
                            } catch (\Exception $e) {
                                // Fallback
                            }
                            
                            return [
                                'id' => $brand->id,
                                'name' => $brand->name,
                                'alias' => $brand->alias,
                                'logo' => $logoUrl,
                                'url' => $brand->alias ? url('/san-pham?brand=' . $brand->id) : '#',
                            ];
                        });
                });
            } catch (\Exception $e) {
                // Fallback nếu cache lỗi - tìm kiếm trực tiếp
                try {
                    $like = '%' . $keyword . '%';
                    $results['brands'] = Brand::where('hidden', 1)
                        ->where('name', 'like', $like)
                        ->orderBy('weight', 'asc')
                        ->orderBy('id', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($brand) {
                            $logoUrl = '';
                            try {
                                if (method_exists($brand, 'getLogoBrand')) {
                                    $logoUrl = $brand->getLogoBrand();
                                } elseif (!empty($brand->logo)) {
                                    $logoUrl = asset('img/brand/' . $brand->logo);
                                }
                            } catch (\Exception $e) {
                                // Fallback
                            }
                            
                            return [
                                'id' => $brand->id,
                                'name' => $brand->name,
                                'alias' => $brand->alias,
                                'logo' => $logoUrl,
                                'url' => $brand->alias ? url('/san-pham?brand=' . $brand->id) : '#',
                            ];
                        });
                } catch (\Exception $e2) {
                    $results['brands'] = collect();
                }
            }
        }

        // Tìm kiếm đối tác
        if ($type === 'all' || $type === 'partner') {
            try {
                $cacheKey = "search_partners_" . md5($keyword);
                $results['partners'] = Cache::remember($cacheKey, 300, function () use ($keyword) {
                    $like = '%' . $keyword . '%';
                    return Partner::where('hidden', 1)
                        ->where(function ($query) use ($like) {
                            $query->where('name', 'like', $like)
                                  ->orWhere('address', 'like', $like)
                                  ->orWhere('city', 'like', $like);
                        })
                        ->orderBy('weight', 'asc')
                        ->orderBy('id', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($partner) {
                            $logoUrl = '';
                            try {
                                if (method_exists($partner, 'getImage')) {
                                    $logoUrl = $partner->getImage();
                                } elseif (!empty($partner->logo)) {
                                    $logoUrl = asset('img/partner/' . $partner->logo);
                                }
                            } catch (\Exception $e) {
                                // Fallback
                            }
                            
                            return [
                                'id' => $partner->id,
                                'name' => $partner->name,
                                'alias' => $partner->alias,
                                'logo' => $logoUrl,
                                'url' => $partner->alias ? route('partner.detail', ['alias' => $partner->alias]) : '#',
                            ];
                        });
                });
            } catch (\Exception $e) {
                // Fallback nếu cache lỗi - tìm kiếm trực tiếp
                try {
                    $like = '%' . $keyword . '%';
                    $results['partners'] = Partner::where('hidden', 1)
                        ->where(function ($query) use ($like) {
                            $query->where('name', 'like', $like)
                                  ->orWhere('address', 'like', $like)
                                  ->orWhere('city', 'like', $like);
                        })
                        ->orderBy('weight', 'asc')
                        ->orderBy('id', 'desc')
                        ->limit(8)
                        ->get()
                        ->map(function ($partner) {
                            $logoUrl = '';
                            try {
                                if (method_exists($partner, 'getImage')) {
                                    $logoUrl = $partner->getImage();
                                } elseif (!empty($partner->logo)) {
                                    $logoUrl = asset('img/partner/' . $partner->logo);
                                }
                            } catch (\Exception $e) {
                                // Fallback
                            }
                            
                            return [
                                'id' => $partner->id,
                                'name' => $partner->name,
                                'alias' => $partner->alias,
                                'logo' => $logoUrl,
                                'url' => $partner->alias ? route('partner.detail', ['alias' => $partner->alias]) : '#',
                            ];
                        });
                } catch (\Exception $e2) {
                    $results['partners'] = collect();
                }
            }
        }

        return response()->json([
            'success' => true,
            'keyword' => $keyword,
            'results' => $results,
            'total' => $results['products']->count() + $results['news']->count() + $results['brands']->count() + $results['partners']->count(),
        ]);
    }
}

