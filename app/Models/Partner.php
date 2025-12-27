<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;
    protected $table = 'partner';
    const IMAGE = 'img/partner/';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'alias',
        'address',
        'city',
        'email',
        'phone',
        'logo',
        'latlng',
        'weight',
        'hidden',
        'content',
    ];

    public function getImage() {
        return asset('img/partner/'. $this->logo);
    }

    public static function getAllPartner() {
        return Partner::orderBy('weight', 'asc')->get();
    }

    public static function getDetailPartner($alias) {
        return Partner::where('alias', $alias)->first();
    }
    
}
