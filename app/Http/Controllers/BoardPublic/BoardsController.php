<?php

namespace App\Http\Controllers\BoardPublic;

use App\Board;
use App\Directory;

class BoardsController extends FrontController
{
    public function show(string $url)
    {
        $current_board = Board::where('url', $url)->firstOrFail();
        return view('public.index')
            ->with('current_board', $current_board)
            ->with('categories', $current_board->categories()->get());
    }

    public function create(string $directory_slug)
    {
        return view('admin.boards.create')
            ->with('directories', Directory::all())
            ->with('force_directory', Directory::where('slug', $directory_slug)->firstOrFail());
    }
}
