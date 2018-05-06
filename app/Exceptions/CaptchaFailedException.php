<?php

namespace App\Exceptions;

class CaptchaFailedException extends BaseException {

    public function __construct($route)
    {
        parent::__construct('warning', 'error', $route, __('auth.captcha-failed'));
    }

}
