<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeaturedCategory extends Model
{
    use HasFactory;
    protected $table = 'featured_category';
    const IMAGE = 'img/featured-category/';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'link',
        'color',
        'status',
        'weight',
        'image',
    ];

    public function getImage() {
        return asset('img/featured-category/'. $this->image);
    }
}
