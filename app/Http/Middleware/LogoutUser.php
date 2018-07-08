<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class LogoutUser {
    public function handle($request, Closure $next) {
        $user = Auth::user();

        if ($user && ($user->to_logout || $user->is_banished)) {
            $level = "";
            $message = "";

            if ($user->to_logout) {
                $level = 'info';
                $message = __('auth.login-again');
                $user->to_logout = false;
                $user->save();
            }

            if ($user->is_banished) {
                $level = 'error';
                $message = 'Prognani ste sa foruma.';
            }

            Auth::logout();
            return alert_redirect(url()->previous(), $level, $message);
        }

        return $next($request);
    }
}
