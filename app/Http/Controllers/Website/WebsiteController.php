<?php

namespace App\Http\Controllers\Website;

use App\Directory;

class WebsiteController
{
    public function index()
    {
        return view('website.index')->with('directories', Directory::all());
    }
}
