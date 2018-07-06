<?php

namespace App\Http\Middleware;

use Auth;
use View;
use Closure;
use App\Board;

class ViewShareBoard {
    public function handle($request, Closure $next) {
        $board = get_board($request->route('board_address'));
        View::share('board', $board);
        View::share('is_admin', $board->is_admin());
        return $next($request);
    }
}
