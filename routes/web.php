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
    Route::get('/{alias}', [ProductController::class, 'detail'])->name('detail');
});

// Tin tức
Route::prefix('tin-tuc')->name('new.')->group(function () {
    Route::get('/', [NewController::class, 'category'])->name('category');
    Route::get('/{alias}', [NewController::class, 'detail'])->name('detail');
});

// Giỏ hàng
Route::get('/gio-hang', [ProductController::class, 'shoppingCart'])->name('cart');
