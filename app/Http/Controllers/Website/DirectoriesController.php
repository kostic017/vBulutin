<?php

namespace App\Http\Controllers\Website;

use App\Directory;

class DirectoriesController
{
    public function show($slug)
    {
        $directory = Directory::where('slug', $slug)->firstOrFail();
        return view('website.directories.show')
            ->with('directory', $directory)
            ->with('boards', $directory->boards()->where('is_visible', true)->get());
    }
}
