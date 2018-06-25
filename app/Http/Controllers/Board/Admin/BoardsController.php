<?php

namespace App\Http\Controllers\Board\Admin;

use App\Board;
use App\Directory;

class BoardsController
{
    public function create($dir_slug)
    {
        return view('admin.boards.create')
            ->with('directories', Directory::all())
            ->with('force_directory', Directory::where('slug', $dir_slug)->firstOrFail());
    }

    public function edit($id)
    {
        return view('admin.boards.edit')
            ->with('directories', Directory::all())
            ->with('board', Board::findOrFail($id));
    }

    public function store()
    {
        $request = request();

        $validator = \Validator::make($request->all(), [
            'url' => 'required|max:255|alpha_dash',
            'title' => 'required|max:255|unique:boards',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $board = new Board;
        $board->url = $request->url;
        $board->owner_id = \Auth::id();
        $board->title = $request->title;
        $board->is_visible = $request->is_visible;
        $board->directory_id = $request->directory;
        $board->description = $request->description;
        $board->save();

        return alert_redirect(route('public.show', ['url' => $board->url]), 'success', 'Forum uspešno napravljen.');
    }
}
