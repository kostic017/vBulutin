<?php

namespace App\Http\Controllers\Website;

use App\Board;

class WebsiteController
{

    public function index() {
        return view('website.index')
            ->with('boards', Board::all());
    }

}
