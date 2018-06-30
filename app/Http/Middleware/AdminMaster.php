<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminMaster
{
    public function handle($request, Closure $next) {
        return (Auth::user()->is_master ?? false) ? $next($request) :
            alert_redirect(url()->previous(), 'error', 'Nemate pravo da pristupite ovoj stranici.');
    }
}
