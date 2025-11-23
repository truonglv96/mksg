<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $table = 'settings';

    const IMAGE = 'img/setting/';

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
