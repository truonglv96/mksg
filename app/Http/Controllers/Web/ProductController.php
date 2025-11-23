<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Trang danh mục sản phẩm
     */
    public function category()
    {
        return view('web.page.products.product-category', [
            'title' => 'Sản Phẩm - Mắt Kính Sài Gòn',
            'type' => 'category',
        ]);
    }

    /**
     * Trang chi tiết sản phẩm
     */
    public function detail($id = null)
    {
        return view('web.page.products.detail', [
            'title' => 'Chi Tiết Sản Phẩm - Mắt Kính Sài Gòn',
            'type' => 'detail',
            'id' => $id,
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

