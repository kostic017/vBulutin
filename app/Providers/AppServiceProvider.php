<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        // Blade::directive('sort_icon', function ($expression) {
        //     [$key, $sortColumn, $sortOrder] = explode(' ', $expression);
        //     $icon = active_class($sortColumn == $key, "ic_s_{$sortOrder}");
        //     return "<span class='icon sort-icon {$icon}'></span>";
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
