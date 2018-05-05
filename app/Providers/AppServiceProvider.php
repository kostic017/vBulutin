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
        Blade::directive('avatar', function ($size) {
            return '<img class="avatar-' . $size . '" src="{{ $user->profile()->first()->avatar ?? asset(\'img/avatar.png\') }}" alt="{{ $user->username }}">';
        });

        Blade::directive('th_sections_sort', function ($column) {
            return
                  '<th data-column="' . $column . '"'
                .   '{!! active_class($sortColumn === \'' . $column . '\', " data-order=\'{$sortOrder}\'") !!}'
                .   '{{ active_class($searchColumn === \'' . $column . '\', \' data-search\') }}'
                . '>'
                .   '<i class="fas fa-search"></i>'
                .   '<a href="#" class="sort-link">{{ __(\'db.' . $column . '\') }}</a>'
                .   '<i class="fas fa-caret-up"></i><i class="fas fa-caret-down"></i>'
                . '</th>';
        });

        Blade::directive('th_users_sort', function ($column) {
            return
                  '<th class="' . $column . '" data-column="' . $column . '"'
                .   '{!! active_class($sortColumn === \'' . $column . '\', " data-order=\'{$sortOrder}\'") !!}'
                . '>'
                .   '<a href="#" class="sort-link">{{ __(\'db.' . $column . '\') }}</a>'
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
