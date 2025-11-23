<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    use HasFactory;

    protected $table = 'brand_images';

    public function getImage() {
        return asset("img/brand/".$this->images);
    }

    public static function getBrandImageByID($id) {
        return BrandImage::where('brand_id', $id)->get();
    }
}
