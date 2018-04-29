<?php

namespace App\Exceptions;

class SomeException extends BaseException {

    public function __construct(Exception $e)
    {
        parent::__construct('notice', 'warning', 'admin.positions', $e->getMessage());
    }

}
