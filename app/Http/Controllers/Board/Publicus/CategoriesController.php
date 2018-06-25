<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Category;

class CategoriesController
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $board = $category->board()->firstOrFail();
        return view('public.category')
            ->with('self', $category)
            ->with('current_board', $board)
            ->with('is_admin', $board->is_admin());
    }
}
