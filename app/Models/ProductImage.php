<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Return_;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'product_images';


    public function getAllImage() {
        if(asset("img/product/".$this->image)) {
            return asset("img/product/".$this->image);
        }
    }

    public static function getImageCategoryProduct($id) {
        return ProductImage::where('product_id', $id)->orderBy('weight', 'ASC')->get();
    }

    public static function getTwoImageCategoryProduct($id) {
        return ProductImage::where('product_id', $id)->orderBy('weight', 'ASC')->limit(2)->get();
    }

    static function getImageByColor($idColor, $idProduct) {
        return ProductImage::where('product_id', $idProduct)->where('color_id', $idColor)->orderBy('weight', 'ASC')->get();
    }
}
