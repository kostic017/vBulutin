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

        if ($user && ($user->to_logout || $user->is_banned)) {
            $message = "";

            if ($user->to_logout) {
                $message = "Morate da se ponovo ulogojete.";
                $user->to_logout = false;
                $user->save();
            }

            if ($user->is_banned) {
                $message = "Banovani ste.";
            }

            Auth::logout();
            return redirect(route('login'))->with([
                'alert-type' => 'info',
                'message' => $message,
            ]);
        }

        return $next($request);
    }
}
