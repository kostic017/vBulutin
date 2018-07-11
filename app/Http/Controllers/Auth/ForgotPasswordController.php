<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller {

    use SendsPasswordResetEmails;

    public function __construct() {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request) {
        $this->validateEmail($request);

        if ($user = User::where('email', $request->email)->first()) {
            if ($user->email_token)
                return alert_redirect(route('password.request'), 'error', __('auth.not-confirmed'));
        } else {
            return alert_redirect(route('password.request'), 'error', __('auth.failed'));
        }

        if (is_captcha_set() && !validate_captcha($request->{'g-recaptcha-response'}, $request->ip())) {
            \App\Helpers\Logger::log($request->ip() . ' has failed captcha.', 'error', __METHOD__);
            return alert_redirect(route('password.request'), 'error', __('auth.captcha-failed'));
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}
