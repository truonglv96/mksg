<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Products;
use App\Models\News;
use App\Models\Brand;

class IndexController extends Controller
{
    /**
     * Trang chủ
     */
    public function index()
    {
        $banners = Slider::where('hidden', 1)->orderBy('weight', 'asc')->get();
        // Chỉ lấy category sản phẩm, loại trừ tin tức
        $categoriesProduct = Category::where('parent_id', 0)
            ->where('type', 'product')
            ->where('hidden', 1)
            ->orderBy('weight', 'asc')
            ->get();
        // Lấy sản phẩm theo từng category
        $productsByCategory = [];
        foreach ($categoriesProduct as $category) {
            $productsByCategory[$category->alias] = Products::getAllProductByParentID($category->id);
        }
        $news = News::where('hidden', 1)->orderBy('created_at', 'desc')->limit(5)->get();
        $brands = Brand::where('hidden', 1)->orderBy('weight', 'asc')->get();
        return view('web.page.home.index', compact('banners', 'categoriesProduct', 'productsByCategory', 'news', 'brands'));
    }

}

