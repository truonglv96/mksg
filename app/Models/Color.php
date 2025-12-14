<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;

    protected $table = 'color';
    const IMAGE = 'img/color/';

    public static function getAllColorAdmin() {
        return Color::orderBy('weight', 'asc')->get();
    }

    public function getImages() {
        return asset('img/color/'. $this->url_img);
    }

    public static function getColorByPhotoID($id) {
        return Color::select('color.*')
        // ->join('product_color', 'product_color.colorID', 'color.id')
        ->join('product_images', 'product_images.color_id', 'color.id')
        ->where('product_images.product_id', $id)->distinct('color.id')->get()->unique('id');
    }

    public static function getColorsByCategoryId($categoryId) {
        // Chỉ lấy colors từ products trực tiếp thuộc category (không bao gồm subcategories)
        $productIds = \App\Models\Products::select('products.id')
            ->join('product_categories', 'product_categories.productID', '=', 'products.id')
            ->where('product_categories.categoryID', $categoryId)
            ->where('products.hidden', 1)
            ->pluck('id');
        
        if ($productIds->isEmpty()) {
            return collect();
        }
        
        return Color::select('color.*')
            ->join('product_images', 'product_images.color_id', '=', 'color.id')
            ->whereIn('product_images.product_id', $productIds)
            ->distinct('color.id')
            ->orderBy('color.weight', 'ASC')
            ->get();
    }

    public static function getColorsByProductIds($productIds) {
        if (empty($productIds) || $productIds->isEmpty()) {
            return collect();
        }
        
        return Color::select('color.*', \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT product_images.product_id) as product_count'))
            ->join('product_images', 'product_images.color_id', '=', 'color.id')
            ->whereIn('product_images.product_id', $productIds->toArray())
            ->groupBy('color.id', 'color.name', 'color.url_img', 'color.weight')
            ->having('product_count', '>', 0)
            ->orderBy('color.weight', 'ASC')
            ->get();
    }

}
