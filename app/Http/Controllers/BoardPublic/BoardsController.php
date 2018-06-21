<?php

namespace App\Http\Controllers\BoardPublic;

use App\Board;

class BoardsController extends FrontController
{
    public function show(string $url)
    {
        $current_board = Board::where('url', $url)->firstOrFail();
        return view('public.index')
            ->with('current_board', $current_board)
            ->with('categories', $current_board->categories()->get());
    }
}
