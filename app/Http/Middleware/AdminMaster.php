<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminMaster
{
    public function handle($request, Closure $next) {
        return (($user = Auth::user()) && $user->is_master) ? $next($request) :
            alert_redirect(url()->previous(), 'error', 'Nemate pravo da pristupite ovoj stranici.');
    }
}
