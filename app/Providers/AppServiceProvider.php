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
        Blade::directive('sort_icon', function ($key) {
            return '<span class="icon sort-icon {{ active_class($sortColumn == ' . $key . ', "ic_s_{$sortOrder}") }}"></span>';
        });
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
