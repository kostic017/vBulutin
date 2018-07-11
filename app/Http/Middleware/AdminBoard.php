<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Board;

class AdminBoard {
    public function handle($request, Closure $next) {
        if (Auth::check()) {
            if (get_board($request->route('board_address'))->is_admin()) {
                return $next($request);
            }
        }
        return alert_redirect('/', 'error', 'Nemate pravo da pristupite ovoj stranici.');
    }
}
