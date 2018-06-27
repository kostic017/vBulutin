<?php

namespace App\Http\Controllers\Website;

use Validator;
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

    public function create()
    {
        return view('website.directories.create');
    }

    public function edit($slug)
    {
        return view('website.directories.edit')
            ->with('directory', Directory::where('slug', $slug)->firstOrFail());
    }

    public function store()
    {
        $request = request();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:directories',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $directory = new Directory;
        $directory->title = $request->title;
        $directory->slug = str_slug($request->title);
        $directory->description = $request->description;
        $directory->save();

        return alert_redirect(route('website.directory.show', ['slug' => $directory->slug]), 'success', 'Direktorijum uspešno napravljen.');
    }

    public function update($id)
    {
        $request = request();

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:directories',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $directory = Directory::findOrFail($id);
        $directory->title = $request->title;
        $directory->slug = str_slug($request->title);
        $directory->description = $request->description;
        $directory->save();

        return alert_redirect(route('website.directory.show', ['slug' => $directory->slug]), 'success', 'Direktorijum uspešno izmenjen.');
    }
}
