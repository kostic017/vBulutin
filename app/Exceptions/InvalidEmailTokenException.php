<?php

namespace App\Exceptions;

class InvalidEmailTokenException extends Base {

    public function __construct($token)
    {
        parent::__construct('notice', 'error', 'login', __('db.not-found.email-token', ['token' => $token]));
    }

}