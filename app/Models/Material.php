<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Material extends Model
{
    use HasFactory;

    protected $table = 'material';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'weight',
    ];

    public static function getMaterialAdmin() {
        return Material::orderBy('weight', 'asc')->get();
    }

    public static function getMaterialsByCategoryId($categoryId) {
        $productIds = \App\Models\Products::select('products.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->where('product_categories.categoryID', $categoryId)
            ->where('products.hidden', 1)
            ->pluck('id');
        
        if ($productIds->isEmpty()) {
            return collect();
        }
        
        return Material::select('material.*', DB::raw('COUNT(DISTINCT products.id) as product_count'))
            ->join('products', 'products.material_id', '=', 'material.id')
            ->whereIn('products.id', $productIds)
            ->where('products.hidden', 1)
            ->groupBy('material.id', 'material.name', 'material.weight')
            ->having('product_count', '>', 0)
            ->orderBy('material.weight', 'ASC')
            ->get();
    }

    public static function getMaterialsByCategoryPath($categoryIds) {
        if (empty($categoryIds)) {
            return collect();
        }
        
        // Lấy product IDs thuộc tất cả categories trong path
        $productIds = \App\Models\Products::select('products.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->whereIn('product_categories.categoryID', $categoryIds)
            ->where('products.hidden', 1)
            ->groupBy('products.id')
            ->havingRaw('COUNT(DISTINCT product_categories.categoryID) = ?', [count($categoryIds)])
            ->pluck('id');
        
        if ($productIds->isEmpty()) {
            return collect();
        }
        
        return Material::select('material.*', DB::raw('COUNT(DISTINCT products.id) as product_count'))
            ->join('products', 'products.material_id', '=', 'material.id')
            ->whereIn('products.id', $productIds)
            ->where('products.hidden', 1)
            ->groupBy('material.id', 'material.name', 'material.weight')
            ->having('product_count', '>', 0)
            ->orderBy('material.weight', 'ASC')
            ->get();
    }
}
