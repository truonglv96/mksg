<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Trang chủ
     */
    public function index()
    {
        return view('web.page.home.index', [
            'title' => 'Mắt Kính Sài Gòn - Trang chủ'
        ]);
    }

    /**
     * Trang danh mục sản phẩm
     */
    public function productCategory()
    {
        return view('web.page.products.product-category', [
            'title' => 'Sản Phẩm - Mắt Kính Sài Gòn',
            'type' => 'category',
        ]);
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function productDetail()
    {
        return view('web.page.products.detail', [
            'title' => 'Chi Tiết Sản Phẩm - Mắt Kính Sài Gòn',
            'type' => 'detail',
        ]);
    }

    /**
     * Trang danh mục tin tức
     */
    public function newCategory()
    {
        return view('web.page.news.new-category', [
            'title' => 'Tin Tức - Mắt Kính Sài Gòn',
            'type' => 'category'
        ]);
    }

    /**
     * Trang chi tiết tin tức
     */
    public function newDetail()
    {
        return view('web.page.news.news-detail', [
            'title' => 'Chi Tiết Tin Tức - Mắt Kính Sài Gòn',
            'type' => 'detail',
        ]);
    }

    /**
     * Trang giỏ hàng
     */
    public function shoppingCart()
    {
        return view('web.page.products.shopping-cart', [
            'title' => 'Giỏ Hàng - Mắt Kính Sài Gòn'
        ]);
    }
}

