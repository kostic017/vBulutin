<?php

namespace App\Exceptions;

class SlugNotFoundException extends BaseException {

    public function __construct(string $slug, string $table)
    {
        parent::__construct('notice', 'info', "{$table}.index", __('db.not-found.slug', compact('slug', 'table')));
    }

}
