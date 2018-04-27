<?php

namespace App\Exceptions;

use Exception;

class SomeException extends Base {

    public function __construct(Exception $e)
    {
        parent::__construct('notice', 'warning', 'admin.positions', $e->getMessage());
    }

}
