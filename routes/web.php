<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\SearchController;

// Trang chủ
Route::get('/', [IndexController::class, 'index'])->name('home');

// Sản phẩm
Route::prefix('san-pham')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'category'])->name('category');
    // Product detail với category path: /san-pham/{categoryPath}/{productAlias}
    Route::get('/{categoryPath}/{productAlias}', [ProductController::class, 'detail'])
        ->where('categoryPath', '[^/]+(/[^/]+)*')
        ->name('detail');
    // Route cho nested categories (phải đặt sau route detail)
    Route::get('/{segments}', [ProductController::class, 'categoryPath'])
        ->where('segments', '[^/]+(/[^/]+)*')
        ->name('category.path');
});

// Tin tức
Route::prefix('tin-tuc')->name('new.')->group(function () {
    // Trang chi tiết bài viết: /tin-tuc/bai-viet/{alias}
    Route::get('/bai-viet/{alias}', [NewsController::class, 'detail'])->name('detail');

    // Trang danh mục tổng: /tin-tuc
    Route::get('/', [NewsController::class, 'category'])->name('category');

    // Trang danh mục theo path (khớp với menu: /tin-tuc/{category}/{child?...})
    Route::get('/{categoryPath}', [NewsController::class, 'category'])
        ->where('categoryPath', '[^/]+(/[^/]+)*')
        ->name('category.path');
});

// Tìm kiếm
Route::post('/search', [SearchController::class, 'search'])->name('search');

// Giỏ hàng
Route::get('/gio-hang', [ProductController::class, 'shoppingCart'])->name('cart');
Route::post('/checkout', [ProductController::class, 'checkout'])->name('checkout');
