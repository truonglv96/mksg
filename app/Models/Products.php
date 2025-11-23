<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Model
{
    use HasFactory;
    protected $table = 'products';
    const IMAGE = 'img/product/';

        

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
}
