<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller {

    use SendsPasswordResetEmails;

    public function __construct() {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request) {
        $this->validateEmail($request);

        if (is_captcha_set() && !validate_captcha($request->{'g-recaptcha-response'}, $request->ip())) {
            \App\Helpers\Logger::log($request->ip() . ' has failed captcha.', 'error', __METHOD__);
            return alert_redirect(url()->previous(), 'error', __('auth.captcha-failed'));
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
}
