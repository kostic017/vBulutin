<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class AdminMaster
{
    public function handle($request, Closure $next)
    {
        return Auth::check() && Auth::user()->is_master ? $next($request) :
            alert_redirect(url()->previous(), 'error', 'Samo master admini mogu pristupati ovoj stranici.');
    }
}
