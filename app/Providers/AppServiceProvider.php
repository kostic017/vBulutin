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
        Blade::directive('sections_th', function ($argument) {
            return
                  '<th data-column="' . $argument . '"'
                .   '{!! active_class($sortColumn === \'' . $argument . '\', " data-order=\'{$sortOrder}\'") !!}'
                .   '{{ active_class($searchColumn === \'' . $argument . '\', \' data-search\') }}'
                . '>'
                .   '<i class="fas fa-search"></i>'
                .   '<a href="#" class="sort-link">{{ __(\'db.' . $argument . '\') }}</a>'
                .   '<i class="fas fa-caret-up"></i><i class="fas fa-caret-down"></i>'
                . '</th>';
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
