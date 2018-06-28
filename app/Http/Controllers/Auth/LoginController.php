<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Helpers\Logger;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    use AuthenticatesUsers {
        redirectPath as laravelRedirectPath;
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
       $login = request()->input('email');
       $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
       request()->merge([$field => $login]);
       return $field;
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if (is_captcha_set() && !validate_captcha($request->{'g-recaptcha-response'}, $request->ip())) {
            Logger::log('error', __METHOD__, $request->ip() . ' has failed captcha.');
            return alert_redirect(url()->previous(), 'error', __('auth.captcha-failed'));
        }

        if ($user = User::where($this->username(), $request[$this->username()])->first()) {
            if (is_empty($user->email_token)) {
                if ($this->attemptLogin($request)) {
                    return $this->sendLoginResponse($request);
                }
            }
        }

        $this->incrementLoginAttempts($request);

        return ($user && is_not_empty($user->email_token)) ?
            alert_redirect(url()->previous(), 'error', __('auth.not-confirmed')) :
            alert_redirect(url()->previous(), 'error', __('auth.failed'));
    }

    protected function authenticated(Request $request, $user)
    {
        return redirect()->back();
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect()->back();
    }

}
