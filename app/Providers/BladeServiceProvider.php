<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider {
    public function boot() {
        Blade::directive('avatar', function ($size) {
            return '<img class="avatar-' . $size . '" src="{{ $user->avatar ?: asset(\'images/avatar.png\') }}" alt="{{ $user->username }}">';
        });

        Blade::directive('th_sort', function () {
            return
                  '<th data-column="{{ $_column }}"'
                . '    {!! active_class($sort_column === $_column, \'data-order="\' . $sort_order . \'"\') !!}'
                . '>'
                . '    <a href="#" class="sort-link">{{ trans("db.columns.$_column") }}</a>'
                . '    <i class="fas fa-caret-up"></i><i class="fas fa-caret-down"></i>'
                . '</th>';
        });
    }
}
