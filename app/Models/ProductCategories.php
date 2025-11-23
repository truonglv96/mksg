<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    use HasFactory;
    protected $table = 'product_categories';


    public static function getCategoryByIDProduct($idProduct) {
        return ProductCategories::select('category.*', 'product_categories.ProductID')
                                    ->leftjoin('category', 'category.id', 'product_categories.CategoryID')
                                    ->where('product_categories.ProductID', $idProduct)
                                    ->first();
    }
}
