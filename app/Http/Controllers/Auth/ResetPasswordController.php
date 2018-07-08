<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\ResetPassword;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends Controller {

    use ResetsPasswords;

    public function __construct() {
        $this->middleware('guest');
    }

    public function sendPasswordResetNotification($token) {
        $this->notify(new ResetPassword($token));
    }

    protected function sendResetResponse($response) {
        return redirect()->back()->with('status', trans($response));
    }

    public function reset(Request $request) {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        if (is_captcha_set() && !validate_captcha($request->{'g-recaptcha-response'}, $request->ip())) {
            \App\Helpers\Logger::log($request->ip() . ' has failed captcha.', 'error', __METHOD__);
            return alert_redirect(url()->previous(), 'error', __('auth.captcha-failed'));
        }

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );

        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($response)
                    : $this->sendResetFailedResponse($request, $response);
    }
}
