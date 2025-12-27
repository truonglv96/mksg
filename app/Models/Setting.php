<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';

    const IMAGE = 'img/setting/';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'facebook',
        'youtube',
        'google_plus',
        'hotline',
        'zalo',
        'email',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'google_analytic',
        'copyright',
        'legal_regulations',
        'facebook_fb',
        'map',
        'logo',
        'order_success',
        'work_time',
        'branch_address',
        'about',
        'address',
        'info',
        'icon_fb',
        'icon_youtube',
        'icon_zalo',
        'icon_email',
        'icon_time',
    ];

    public function getLogo() {
        return asset('img/setting/'. $this->logo);
    }

    public function getIconFB() {
        return asset('img/setting/'. $this->icon_fb);
    }

    public function getIconYoutube() {
        return asset('img/setting/'. $this->icon_youtube);
    }

    public function getIconZalo() {
        return asset('img/setting/'. $this->icon_zalo);
    }

    public function getIconEmail() {
        return asset('img/setting/'. $this->icon_email);
    }

    public function getIconTime() {
        return asset('img/setting/'. $this->icon_time);
    }
}
