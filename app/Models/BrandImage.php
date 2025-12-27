<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    use HasFactory;

    protected $table = 'brand_images';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'brand_id',
        'images',
    ];

    public function getImage() {
        return asset("img/brand/".$this->images);
    }

    public static function getBrandImageByID($id) {
        return BrandImage::where('brand_id', $id)->get();
    }
}
