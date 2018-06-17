<?php

namespace App\Http\Controllers\Website;

use App\Board;
use App\Directory;

class WebsiteController
{
    public function index()
    {
        return view('website.index')->with('directories', Directory::all());
    }

    public function directory($slug)
    {
        $directory = Directory::where('slug', $slug)->firstOrFail();
        return view('website.directory')
            ->with('directory', $directory)
            ->with('boards', $directory->boards()->where('is_visible', true)->get());
    }
}
