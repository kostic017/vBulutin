<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\BannedUser;

class RedirectIfBanned {
    public function handle($request, Closure $next) {
        if ($user = Auth::user()) {
            $board = get_board($request->route('board_address'));
            if (BannedUser::where('user_id', $user->id)->where('board_id', $board->id)->count()) {
                return alert_redirect(route('website.index'), 'error', 'Banovani ste na ovom forumu.');
            }
        }

        return $next($request);
    }
}
