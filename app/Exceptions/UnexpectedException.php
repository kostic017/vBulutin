<?php

namespace App\Exceptions;

class UnexpectedException extends BaseException {

    public function __construct(Exception $e)
    {
        parent::__construct('notice', 'warning', 'admin.positions', $e->getMessage());
    }

}
