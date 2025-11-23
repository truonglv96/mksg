<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * Trang danh mục tin tức
     */
    public function category()
    {
        return view('web.page.news.new-category', [
            'title' => 'Tin Tức - Mắt Kính Sài Gòn',
            'type' => 'category'
        ]);
    }

    /**
     * Trang chi tiết tin tức
     */
    public function detail($id = null)
    {
        return view('web.page.news.detail', [
            'title' => 'Chi Tiết Tin Tức - Mắt Kính Sài Gòn',
            'type' => 'detail',
            'id' => $id,
        ]);
    }
}

