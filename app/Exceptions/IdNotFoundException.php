<?php

namespace App\Exceptions;

class IdNotFoundException extends BaseException {

    public function __construct($id, $table)
    {
        parent::__construct('notice', 'info', "{$table}.index", __('db.not-found.id', compact('id', 'table')));
    }

}
