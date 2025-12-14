<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    const IMAGE = 'img/product/';

    /**
     * Lấy category path từ product (lấy category cuối cùng - deepest và build full path)
     */
    public function getCategoryPath() {
        $categories = Category::select('category.*')
            ->join('product_categories', 'product_categories.CategoryID', '=', 'category.id')
            ->where('product_categories.ProductID', $this->id)
            ->where('category.type', 'product')
            ->where('category.hidden', 1)
            ->orderBy('category.parent_id', 'DESC') // Lấy category con nhất trước
            ->orderBy('category.weight', 'ASC')
            ->get();
        
        if ($categories->isEmpty()) {
            return '';
        }
        
        // Lấy category cuối cùng (deepest) - có parent_id lớn nhất hoặc không có con
        $deepestCategory = $categories->first();
        foreach ($categories as $cat) {
            $hasChild = $categories->contains(function($c) use ($cat) {
                return $c->parent_id == $cat->id;
            });
            if (!$hasChild) {
                $deepestCategory = $cat;
                break;
            }
        }
        
        return $deepestCategory->getFullPath();
    }
        

    public static function getAllProductAmdin() {
        return Products::orderBy('id', 'desc')->orderBy('weight', 'ASC')->paginate(15);
        // return Products::orderBy('weight', 'ASC')->get();
        //     Schema::table('products', function (Blueprint $table) {
        //         $table->index('id');
        //         $table->index('name');
        //         $table->index('price_sale');
        //         $table->index('hidden');
        // });

        // return Products::chunk(100, function($products){
        //     foreach ($products as $product){
        //         dd($product);
        //         $product->id;
        //         $product->name;
        //         $product->price_sale;
        //         $product->hidden;
        //     }
        //  });
    }

    public static function searcAllProductCategoriesBrand($dataRequest) {
        $sql = Products::select('products.*')
            ->leftjoin('product_categories', 'product_categories.ProductID', '=', 'products.id')
            ->leftjoin('category', 'category.id', '=', 'product_categories.CategoryID')
            //  brand
            ->leftjoin('brand', 'brand.id', '=', 'products.brand_id');
            // ->where('category.id',$dataRequest['category'])
            // ->toSql();
        // dd($dataRequest);
        if(!empty($dataRequest['category'] == "-1") && !empty($dataRequest['brand'] == "-1")){
            if(!empty($dataRequest['keywork'])) {
                $sql->where('products.name', 'LIKE', '%'. $dataRequest['keywork'] .'%');
            }
        }else {
            if(!empty($dataRequest['category'] == "-1")) {
                if(!empty($dataRequest['brand'])) {
                    $sql->where('brand.id', $dataRequest['brand']);
                }
            }elseif(!empty($dataRequest['brand'] == "-1")) {
                if(!empty($dataRequest['category'])) {
                    $sql->where('category.id', $dataRequest['category']);
                }
            }elseif(!empty($dataRequest['brand'] == "-1") && !empty($dataRequest['category'] == "-1")) {
                if(!empty($dataRequest['keywork'])) {
                    $sql->where('products.name', 'LIKE', '%'. $dataRequest['keywork'] .'%');
                }
            }else {
                if(!empty($dataRequest['category'])) {
                    $sql->where('category.id', $dataRequest['category']);
                }
                if(!empty($dataRequest['brand'])) {
                    $sql->where('brand.id', $dataRequest['brand']);
                }
                if(!empty($dataRequest['keywork'])) {
                    $sql->where('products.name', 'LIKE', '%'. $dataRequest['keywork'] .'%');
                }
            }   
        }
        

        return $sql->distinct('products.id')->orderBy('products.id', 'DESC')->orderBy('products.weight', 'ASC')->paginate(15);
    }

    public function categoriesProductByID() {
        $categories =  Category::getByProductID($this->id);
        $categoriesList = [];
        foreach ($categories as $category) {
            $categoriesList[$category->id] = $category;
        }
        return $categoriesList;
    
    }
    // Web

    // page home
    public static function getAllProductByParentID($idParent) {
        return Products::select('products.*')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->where('product_categories.categoryID', '=', $idParent)
            ->where('products.hidden', 1)
            ->orderBy('id', 'DESC')
            ->orderBy('weight', 'ASC')
            ->limit(10)->get();
    }

    public static function getProductByCategory($cateID, $limit = 12) {
        // return Products::select('products.*', 'category.name as cateName','category.id as cateID','category.alias as cateAlias', 'color.id as colorID', 'color.name as colorName', 'color.url_img as colorImg')
        return Products::select('products.*', 'category.name as cateName','category.id as cateID','category.alias as cateAlias')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->join('category', 'category.id', '=', 'product_categories.CategoryID')
            // ->join('product_color', 'product_color.productID', '=', 'products.id')
            // ->join('color', 'color.id', '=', 'product_color.colorID')
            ->where('category.id', $cateID)
            // ->orderBy('id', 'desc')
            // ->orderBy('created_at', 'desc')
            ->orderBy('products.id', 'DESC')
            ->orderBy('weight', 'asc')
            ->paginate($limit);
    }

    /**
     * @param array $categories
     * @param int $limit
     * @return mixed
     */
    public static function getProductByCategories($data, $limit = 12) {
        $sql = Products::select('products.*', 'category.name as cateName', 'category.alias as cateAlias')
            ->leftjoin('product_categories', 'product_categories.productID', '=', 'products.id')
            ->leftjoin('category', 'category.id', '=', 'product_categories.CategoryID')
            //  brand
            ->leftjoin('brand', 'brand.id', '=', 'products.brand_id');
             if(!empty($data['price'])) {
                 $sql->whereBetween('products.price', [0, $data['price']]);
             }
            if(!empty($data['brand'])) {
                $sql->whereIn('brand.id', $data['brand']);
            }
            if(!empty($data['category'])) {
                $sql->whereIn('category.id', $data['category']);
            }
            return $sql->distinct('products.id')->orderBy('products.id', 'DESC')->orderBy('products.weight', 'ASC')->paginate($limit);
    }


    public static function getProductByAlias($alias) {
        return Products::where('alias', $alias)->first();
    }


    public static function getProductOrtherByIDCategory($id) {
        return Products::select('products.*')
                        ->leftjoin('product_categories', 'product_categories.productID', '=', 'products.id')
                        ->where('product_categories.CategoryID', '=', $id)
                        ->where('products.hidden', 1)
                        ->orderBy('id', 'desc')
                        ->orderBy('weight', 'ASC')
                        ->limit(12)
                        ->get();
    }

    public static function searchProduct($keyword, $limit) {
        return Products::where('name', 'LIKE', '%'. $keyword .'%')->orderBy('weight', 'ASC')->paginate($limit);
    }

    public static function getProductBySearch($dataRequest) {
        $sql = Products::select('products.*', 'category.name as cateName', 'category.alias as cateAlias')
            ->leftjoin('product_categories', 'product_categories.productID', '=', 'products.id')
            ->leftjoin('category', 'category.id', '=', 'product_categories.CategoryID')
            ->leftjoin('brand', 'brand.id', '=', 'products.brand_id')
            ->where('products.hidden', 1);

        if(!empty($dataRequest['price'])) {
            $sql->whereBetween('products.price', [0, $dataRequest['price']]);
        }
        if(!empty($dataRequest['brand'])) {
            $sql->whereIn('brand.id', $dataRequest['brand']);
        }
        if(!empty($dataRequest['category'])) {
            $sql->whereIn('category.id', $dataRequest['category']);
        }
        if(!empty($dataRequest['keywork'])) {
            $sql->where('products.name', 'LIKE', '%'. $dataRequest['keywork'] .'%');
        }

        return $sql->distinct('products.id')->orderBy('products.id', 'DESC')->orderBy('products.weight', 'ASC')->paginate(15);
    }

    public static function getProductByCategoryPath($categoryIds, $lastCategoryId, $limit = 12, $sort = 'newest', $filters = []) {
        // Lấy products thuộc category cuối cùng (không cần thuộc tất cả categories trong path)
        $productIds = Products::select('products.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->where('product_categories.CategoryID', $lastCategoryId)
            ->where('products.hidden', 1)
            ->distinct('products.id')
            ->pluck('id');
        
        $query = Products::select('products.*', 'category.name as cateName', 'category.id as cateID', 'category.alias as cateAlias')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->join('category', 'category.id', '=', 'product_categories.CategoryID')
            ->whereIn('products.id', $productIds)
            ->where('category.id', $lastCategoryId)
            ->where('products.hidden', 1);
        
        // Filter by price
        if (!empty($filters['price_min']) || !empty($filters['price_max'])) {
            $priceMin = $filters['price_min'] ?? 0;
            $priceMax = $filters['price_max'] ?? 999999999;
            $query->where(function($q) use ($priceMin, $priceMax) {
                $q->whereBetween('products.price_sale', [$priceMin, $priceMax])
                  ->orWhereBetween('products.price', [$priceMin, $priceMax]);
            });
        }
        
        // Filter by color
        if (!empty($filters['color_id'])) {
            $colorProductIds = Products::select('products.id')
                ->join('product_images', 'product_images.product_id', '=', 'products.id')
                ->where('product_images.color_id', $filters['color_id'])
                ->where('products.hidden', 1)
                ->pluck('id');
            
            if ($colorProductIds->isNotEmpty()) {
                $query->whereIn('products.id', $colorProductIds);
            } else {
                $query->whereRaw('1 = 0'); // No results
            }
        }
        
        // Filter by material
        if (!empty($filters['material_id'])) {
            $query->where('products.material_id', $filters['material_id']);
        }
        
        // Filter by brand
        if (!empty($filters['brand_id'])) {
            $query->where('products.brand_id', $filters['brand_id']);
        }
        
        switch($sort) {
            case 'price_asc':
                $query->orderBy('products.price_sale', 'ASC')->orderBy('products.price', 'ASC');
                break;
            case 'price_desc':
                $query->orderBy('products.price_sale', 'DESC')->orderBy('products.price', 'DESC');
                break;
            case 'name_asc':
                $query->orderBy('products.name', 'ASC');
                break;
            case 'name_desc':
                $query->orderBy('products.name', 'DESC');
                break;
            default: // newest
                $query->orderBy('products.id', 'DESC')->orderBy('products.weight', 'ASC');
        }
        
        return $query->distinct('products.id')->paginate($limit);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_color', 'productID', 'colorID');
    }

    public static function getPriceRangesByCategoryId($categoryId) {
        $productIds = Products::select('products.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->where('product_categories.categoryID', $categoryId)
            ->where('products.hidden', 1)
            ->pluck('id');
        
        if ($productIds->isEmpty()) {
            return [
                'under_500k' => 0,
                '500k_1m' => 0,
                '1m_3m' => 0,
                '3m_5m' => 0,
                'over_5m' => 0,
            ];
        }
        
        $products = Products::whereIn('id', $productIds)->get();
        
        return [
            'under_500k' => $products->filter(fn($p) => ($p->price_sale ?? $p->price ?? 0) < 500000)->count(),
            '500k_1m' => $products->filter(fn($p) => ($price = $p->price_sale ?? $p->price ?? 0) >= 500000 && $price < 1000000)->count(),
            '1m_3m' => $products->filter(fn($p) => ($price = $p->price_sale ?? $p->price ?? 0) >= 1000000 && $price < 3000000)->count(),
            '3m_5m' => $products->filter(fn($p) => ($price = $p->price_sale ?? $p->price ?? 0) >= 3000000 && $price < 5000000)->count(),
            'over_5m' => $products->filter(fn($p) => ($p->price_sale ?? $p->price ?? 0) >= 5000000)->count(),
        ];
    }
}
