<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCategory extends Model
{
    use HasFactory;
    protected $table = 'featured_category';
    const IMAGE = 'img/featured-category/';

    public function getImage() {
        return asset('img/featured-category/'. $this->image);
    }
}
