<?php

namespace App\Exceptions;

use Exception;

class DataNotFoundException extends Exception {

    private $id;
    private $table;

    public function __construct($table, $id) {
        $this->id = $id;
        $this->table = $table;
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return redirect(route("{$this->table}.index"))->with([
            'alert-type' => 'info',
            'message' => __('db.not-found', [
                'table' => $this->table,
                'id' => $this->id
            ])
        ]);
    }
}
