<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class LogoutUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->to_logout) {
            Auth::logout();

            $user->to_logout = false;
            $user->save();

            return redirect(route('login'));
        }

        return $next($request);
    }
}
