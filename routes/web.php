<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\NewController;

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
    Route::get('/', [NewController::class, 'category'])->name('category');
    Route::get('/{alias}', [NewController::class, 'detail'])->name('detail');
});

// Giỏ hàng
Route::get('/gio-hang', [ProductController::class, 'shoppingCart'])->name('cart');
Route::post('/checkout', [ProductController::class, 'checkout'])->name('checkout');
