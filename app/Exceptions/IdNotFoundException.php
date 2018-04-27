<?php

namespace App\Exceptions;

class IdNotFoundException extends Base {

    public function __construct($id, $table)
    {
        parent::__construct('notice', 'info', "{$table}.index", __('db.not-found.id', compact('id', 'table')));
    }

}
