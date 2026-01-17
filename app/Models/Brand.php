<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Brand extends Model
{
    use HasFactory;
    const IS_ACTIVE = 1;
    const IMAGE = 'img/brand/';
    protected $table = 'brand';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'alias',
        'content',
        'url_imgs',
        'weight',
        'hidden',
        'logo',
    ];

    /**
     * Get brand images
     */
    public function images()
    {
        return $this->hasMany(BrandImage::class, 'brand_id');
    }

    public function getLogoBrand() {
        return asset('img/brand/'. $this->logo);
    }

    public function getAllImage() {
        if(asset("img/brand/".$this->images)) {
            return asset("img/brand/".$this->images);
        }
    }

    public function getThump() {
        $images =  json_decode($this->url_imgs, true);
        if($images) {
            $fristImage = array_shift($images);
            if($fristImage) {
                return asset("img/brand/".$fristImage);
            }
        }else {
            return asset('img/brand/'. $this->url_imgs);
        }
        return '';
    }

    public function getAllImages() {
        $images = json_decode($this->url_imgs, true);
        if($images && is_array($images)) {
            return array_map(function($image) {
                return asset("img/brand/".$image);
            }, $images);
        } elseif($this->url_imgs) {
            return [asset('img/brand/'. $this->url_imgs)];
        }
        return [];
    }


    public static function getAllBrandAdmin() {
        return Brand::orderBy('weight', 'asc')->get();
    }

    
    // Web
    public static function getAllBrandHome() {
        return Brand::where('hidden', 1)->orderBy('weight', 'asc')->get();
    }

    public static function getAllBrandIDByCategoryID($cateID) {
        return Brand::select('brand.*', 'category.name as cateName' ,'category.id as cateID')
            ->join('products', 'products.brand_id', '=', 'brand.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->join('category', 'category.id', '=', 'product_categories.CategoryID')
            ->where('category.id', '=', $cateID)
            ->distinct('brand.id')
            ->orderBy('weight', 'ASC')
            ->get();
            // ->paginate(1000);
    }

    public static function getBrandByID($id) {
        return Brand::where('id', $id)->first();
    }



    public static function getAllBrandByCategory($CategoryID, $limit = 16) {
        $brand = Brand::select('brand.*',  'category.id as categoriesID')
                        ->join('products', 'products.brand_id', '=', 'brand.id')
                        ->join('product_categories', 'product_categories.productID', '=', 'products.id')
                        ->join('category', 'category.id', '=', 'product_categories.categoryID')
                        ->where('category.id', $CategoryID)
                        ->distinct('brand.id')
                        // ->get();
                        ->paginate($limit);

        // $brand = DB::table('brand')
        //             ->select('brand.*',  'category.id as categoriesID')
        //             ->from('brand')
        //             ->leftJoin('products', 'products.brand_id', '=', 'brand.id')
        //             ->leftJoin('product_categories', 'product_categories.productID', '=', 'products.id')
        //             ->leftJoin('category', 'category.id', '=', 'product_categories.categoryID')
        //             ->where('category.id', $CategoryID)
        //             ->distinct('brand.id')
        //             ->paginate($limit);
        // ->distinct()->get();

        return $brand;
    }


    public static function getAllBrand($limit = 28) {
        return Brand::where('hidden', Brand::IS_ACTIVE)->orderBy('weight', 'ASC')->paginate($limit);
    }


    public static function getDetailBrandByAlias($alias) {
        return Brand::where('alias', $alias)->first(); 
    }


    public static function getCategoryByBrandIDProduct($IDBrand) {
        return Brand::select('category.*')
                        ->join('products', 'products.brand_id', 'brand.id')
                        ->join('product_categories', 'product_categories.ProductID', 'products.id')
                        ->join('category', 'category.id', 'product_categories.CategoryID')
                        ->where('brand.id', $IDBrand)
                        ->distinct('category.id')
                        ->get();
    }

    public static function getMarterialByBrandIDProduct($IDBrand) {
        return Brand::select('material.*' , 'products.id as prodID', 'brand.id as brandID')
                        ->join('products', 'products.brand_id', 'brand.id')
                        ->join('material', 'material.id', 'products.material_id')
                        ->where('brand.id', $IDBrand)
                        ->orderBy('material.id', 'desc')
                        ->distinct('material.id')
                        ->get();
    }
    
    public static function getAllProductByMaterial($id, $limit) {
        return  Brand::select('products.*', 'material.id as materialID')
                        ->join('products', 'products.brand_id', 'brand.id')
                        ->join('material', 'material.id', 'products.material_id')
                        ->whereIn('products.id', $id)
                        ->orderBy('products.id', 'desc')
                        ->paginate($limit);
    }

    public static function searchAllProductByMaterialID($id, $idBrand, $limit) {
        return  Brand::select('products.*', 'material.id as materialID')
                        ->join('products', 'products.brand_id', 'brand.id')
                        ->join('material', 'material.id', 'products.material_id')
                        ->where('material.id', $id)
                        ->where('brand.id', $idBrand)
                        ->orderBy('products.id', 'desc')
                        ->paginate($limit);
    }

    public static function getBrandsByCategoryPath($categoryIds, $lastCategoryId) {
        if (empty($categoryIds) || empty($lastCategoryId)) {
            return collect();
        }
        
        // Lấy product IDs thuộc category cuối cùng (không cần thuộc tất cả categories trong path)
        $productIds = \App\Models\Products::select('products.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->where('product_categories.categoryID', $lastCategoryId)
            ->where('products.hidden', 1)
            ->distinct('products.id')
            ->pluck('id');
        
        // Nếu không có products, trả về empty collection
        if ($productIds->isEmpty()) {
            return collect();
        }
        
        // Chỉ lấy brands từ những products thuộc category cuối cùng
        return Brand::select('brand.*', DB::raw('COUNT(DISTINCT products.id) as product_count'))
            ->join('products', 'products.brand_id', '=', 'brand.id')
            ->whereIn('products.id', $productIds->toArray())
            ->where('products.hidden', 1)
            ->where('brand.hidden', 1)
            ->groupBy('brand.id', 'brand.name', 'brand.alias', 'brand.content', 'brand.url_imgs', 'brand.weight', 'brand.hidden', 'brand.logo', 'brand.created_at', 'brand.updated_at')
            ->having('product_count', '>', 0)
            ->orderBy('brand.weight', 'ASC')
            ->get();
    }
}
