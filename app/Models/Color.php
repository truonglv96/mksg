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


}
