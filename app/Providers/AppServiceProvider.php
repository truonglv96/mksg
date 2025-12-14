<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\View\Composers\MasterComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share data với master layout
        // View::composer('web.master', MasterComposer::class);
        
        // Hoặc nếu muốn share với tất cả views trong web
        View::composer('web.*', MasterComposer::class);
    }
}
