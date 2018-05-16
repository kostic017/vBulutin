<?php

namespace App\Exceptions;

class RowNotFoundException extends BaseException {

    public function __construct(string $slug, string $table)
    {
        parent::__construct('notice', 'info', "{$table}.index", __('db.not-found.row', compact('slug', 'table')));
    }

}
