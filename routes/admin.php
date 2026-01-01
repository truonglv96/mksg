<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\MaterialController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\FeaturedCategoryController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\FeaturesProductController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Định tuyến cho khu vực quản trị (Admin Panel)
|
*/

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth Routes (public) - chỉ cho phép nếu chưa đăng nhập
    Route::middleware(['admin.guest'])->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });
    
    // Logout route - yêu cầu đăng nhập
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('admin.auth');
    
    // Protected Routes - yêu cầu đăng nhập
    Route::middleware(['admin.auth'])->group(function () {
        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

        // Profile
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

        // Products
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::get('/create', [ProductController::class, 'create'])->name('create');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{id}', [ProductController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');
    });

        // Categories
        Route::prefix('categories')->name('categories.')->group(function () {
            Route::get('/', [CategoryController::class, 'index'])->name('index');
            Route::get('/create', [CategoryController::class, 'create'])->name('create');
            Route::post('/', [CategoryController::class, 'store'])->name('store');
            Route::post('/reorder', [CategoryController::class, 'reorder'])->name('reorder');
            Route::get('/{id}', [CategoryController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');
        });

        // Orders
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::put('/{id}/status', [OrderController::class, 'updateStatus'])->name('updateStatus');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        });

        // Customers
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
            Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
        });

        // News
        Route::prefix('news')->name('news.')->group(function () {
            Route::get('/', [NewsController::class, 'index'])->name('index');
            Route::get('/create', [NewsController::class, 'create'])->name('create');
            Route::post('/', [NewsController::class, 'store'])->name('store');
            Route::get('/{id}', [NewsController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [NewsController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NewsController::class, 'update'])->name('update');
            Route::delete('/{id}', [NewsController::class, 'destroy'])->name('destroy');
        });

        // Brands
        Route::prefix('brands')->name('brands.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::get('/create', [BrandController::class, 'create'])->name('create');
            Route::post('/', [BrandController::class, 'store'])->name('store');
            Route::get('/{id}', [BrandController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [BrandController::class, 'edit'])->name('edit');
            Route::put('/{id}', [BrandController::class, 'update'])->name('update');
            Route::delete('/{id}', [BrandController::class, 'destroy'])->name('destroy');
        });

        // Sliders
        Route::prefix('sliders')->name('sliders.')->group(function () {
            Route::get('/', [SliderController::class, 'index'])->name('index');
            Route::post('/', [SliderController::class, 'store'])->name('store');
            Route::get('/{id}', [SliderController::class, 'getSlider'])->name('get');
            Route::put('/{id}', [SliderController::class, 'update'])->name('update');
            Route::delete('/{id}', [SliderController::class, 'destroy'])->name('destroy');
        });

        // Store Information (Contacts)
        Route::prefix('store-information')->name('store-information.')->group(function () {
            Route::get('/', [ContactController::class, 'index'])->name('index');
            Route::post('/', [ContactController::class, 'store'])->name('store');
            Route::get('/{id}', [ContactController::class, 'getContact'])->name('get');
            Route::put('/{id}', [ContactController::class, 'update'])->name('update');
            Route::delete('/{id}', [ContactController::class, 'destroy'])->name('destroy');
        });

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingController::class, 'index'])->name('index');
            Route::put('/', [SettingController::class, 'update'])->name('update');
        });

        // Materials
        Route::prefix('materials')->name('materials.')->group(function () {
            Route::get('/', [MaterialController::class, 'index'])->name('index');
            Route::post('/', [MaterialController::class, 'store'])->name('store');
            Route::get('/{id}', [MaterialController::class, 'getMaterial'])->name('get');
            Route::put('/{id}', [MaterialController::class, 'update'])->name('update');
            Route::delete('/{id}', [MaterialController::class, 'destroy'])->name('destroy');
        });

        // Colors
        Route::prefix('colors')->name('colors.')->group(function () {
            Route::get('/', [ColorController::class, 'index'])->name('index');
            Route::post('/', [ColorController::class, 'store'])->name('store');
            Route::get('/{id}', [ColorController::class, 'getColor'])->name('get');
            Route::put('/{id}', [ColorController::class, 'update'])->name('update');
            Route::delete('/{id}', [ColorController::class, 'destroy'])->name('destroy');
        });

        // Featured Categories
        Route::prefix('featured-categories')->name('featured-categories.')->group(function () {
            Route::get('/', [FeaturedCategoryController::class, 'index'])->name('index');
            Route::post('/', [FeaturedCategoryController::class, 'store'])->name('store');
            Route::get('/{id}', [FeaturedCategoryController::class, 'getFeaturedCategory'])->name('get');
            Route::put('/{id}', [FeaturedCategoryController::class, 'update'])->name('update');
            Route::delete('/{id}', [FeaturedCategoryController::class, 'destroy'])->name('destroy');
        });

        // Features Product
        Route::prefix('features-product')->name('features-product.')->group(function () {
            Route::get('/', [FeaturesProductController::class, 'index'])->name('index');
            Route::post('/', [FeaturesProductController::class, 'store'])->name('store');
            Route::get('/{id}', [FeaturesProductController::class, 'getFeaturesProduct'])->name('get');
            Route::put('/{id}', [FeaturesProductController::class, 'update'])->name('update');
            Route::delete('/{id}', [FeaturesProductController::class, 'destroy'])->name('destroy');
        });
    });
});

