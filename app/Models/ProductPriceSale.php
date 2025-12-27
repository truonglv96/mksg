<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class ProductPriceSale extends Model
{
    use HasFactory;
    protected $table = 'product_price_sales';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_category',
        'parent_category',
        'id_Product',
        'order',
        'discount',
        'price',
        'status',
    ];

    public static function getPriceSaleByIDProduct($idProduct) {
        return ProductPriceSale::where('id_Product', $idProduct)->get();
    }
     
    public function mainCategory(){
        return $this->belongsTo(Category::class, 'parent_category');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'id_category');
    }
}
