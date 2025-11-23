<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $table = 'slider';


    // Admin
    public static function getSliderAdmin() {
        return Slider::orderBy('weight', 'ASC')->get();
    }

    // web
    const IMAGE = 'img/slider/';

    public function getImage() {
        return asset('img/slider/'. $this->url_img);
    }
    public static function getSliderWeb() {
        return Slider::where('hidden', 1)->orderBy('id', 'desc')->orderBy('weight', 'ASC')->get();
    }
}
