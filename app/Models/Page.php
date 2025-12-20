<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    protected $table = 'pages';

    const IMAGE = 'img/page/';

    const IS_ACTIVE = 1;


    public function getImage() {
        return asset('img/page/'. $this->image);
    }

    public static function getAllPageAdmin() {
        return Page::orderBy('weight', 'asc')->get();
    }

    public static function getPoliciesAndRegulations() {
        return Page::where('type', 0)->orderBy('weight', 'asc')->get();
    }

    public static function getDetailPage($link) {
        return Page::where('link', $link)->first();
    }

    public static function getDetailPageByAlias($alias) {
        return Page::where('link', $alias)->first();
    }
}
