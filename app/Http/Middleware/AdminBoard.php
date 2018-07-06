<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Board;

class AdminBoard
{
    public function handle($request, Closure $next) {
        if (Auth::check()) {
            $address = $request->route('board_address');
            $board = get_board($address);
            if ($board->is_admin()) {
                return $next($request);
            }
        }
        return alert_redirect(url()->previous(), 'error', 'Nemate pravo da pristupite ovoj stranici.');
    }
}
