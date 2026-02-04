<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\IndexController;
use App\Http\Controllers\Web\ProductController;
use App\Http\Controllers\Web\NewsController;
use App\Http\Controllers\Web\SearchController;
use App\Http\Controllers\Web\BrandController;
use App\Http\Controllers\Web\PartnerController;
use App\Http\Controllers\Web\PageController;

// Trang chủ
Route::get('/', [IndexController::class, 'index'])->name('home');

// Sản phẩm
Route::prefix('bai-viet-san-pham')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'category'])->name('category');
    // Product detail với category path: /bai-viet-san-pham/{categoryPath}/{productAlias}
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

// Thương hiệu
Route::prefix('thuong-hieu')->name('brand.')->group(function () {
    Route::get('/', [BrandController::class, 'index'])->name('index');
    Route::get('/{alias}', [BrandController::class, 'detail'])->name('detail');
});

// Đối tác
Route::prefix('doi-tac')->name('partner.')->group(function () {
    Route::get('/', [PartnerController::class, 'index'])->name('index');
    Route::get('/{alias}', [PartnerController::class, 'detail'])->name('detail');
});

// Page detail
Route::prefix('trang')->name('page.')->group(function () {
    Route::get('/{alias}', [PageController::class, 'detail'])->name('detail');
});

// Tìm kiếm
Route::post('/search', [SearchController::class, 'search'])->name('search');

// Giỏ hàng
Route::get('/gio-hang', [ProductController::class, 'shoppingCart'])->name('cart');
Route::post('/checkout', [ProductController::class, 'checkout'])->name('checkout');

// Clear Cache
Route::get('/clear-cache', function () {
    $results = [];
    
    // try {
    //     \Illuminate\Support\Facades\Artisan::call('cache:clear');
    //     $results['cache'] = 'Application cache cleared';
    // } catch (\Exception $e) {
    //     $results['cache'] = 'Cache clear skipped: ' . $e->getMessage();
    // }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        $results['config'] = 'Configuration cache cleared';
    } catch (\Exception $e) {
        $results['config'] = 'Config clear skipped: ' . $e->getMessage();
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        $results['view'] = 'View cache cleared';
    } catch (\Exception $e) {
        $results['view'] = 'View clear skipped: ' . $e->getMessage();
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        $results['route'] = 'Route cache cleared';
    } catch (\Exception $e) {
        $results['route'] = 'Route clear skipped: ' . $e->getMessage();
    }
    
    return response()->json([
        'success' => true,
        'message' => 'Cache clear completed!',
        'details' => $results
    ]);
})->name('clear.cache');

// Admin Routes
require __DIR__.'/admin.php';

// Route alias for login (for Laravel's auth middleware)
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
