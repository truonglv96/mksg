<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IndexController;

// Trang chủ
Route::get('/', [IndexController::class, 'index'])->name('home');

// Sản phẩm
Route::prefix('san-pham')->name('product.')->group(function () {
    Route::get('/', [IndexController::class, 'productCategory'])->name('category');
    Route::get('/detail', [IndexController::class, 'productDetail'])->name('detail');
});

// Tin tức
Route::prefix('tin-tuc')->name('new.')->group(function () {
    Route::get('/', [IndexController::class, 'newCategory'])->name('category');
    Route::get('/{id}', [IndexController::class, 'newDetail'])->name('detail');
});

// Giỏ hàng
Route::get('/gio-hang', [IndexController::class, 'shoppingCart'])->name('cart');
