<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider {
    public function boot() {
        Blade::directive('avatar', function ($size) {
            return '<img class="avatar-' . $size . '" src="{{ $user->profile->avatar ?? asset(\'img/avatar.png\') }}" alt="{{ $user->username }}">';
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
}
