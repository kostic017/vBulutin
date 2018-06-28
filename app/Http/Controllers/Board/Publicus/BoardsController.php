<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Board;

class BoardsController
{
    public function show($address)
    {
        $board = Board::where('address', $address)->firstOrFail();

        return view('public.index')
            ->with('current_board', $board)
            ->with('is_admin', $board->is_admin())
            ->with('categories', $board->categories()->get());
    }
}
