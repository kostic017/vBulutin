<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Board;

class BoardsController
{
    public function show(string $url)
    {
        $board = Board::where('url', $url)->firstOrFail();
        return view('public.index')
            ->with('board', $board)
            ->with('is_admin', $board->is_admin())
            ->with('categories', $board->categories()->get());
    }
}
