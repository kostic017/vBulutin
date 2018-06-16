<?php

namespace App\Http\Controllers\Front;

use App\Category;

class CategoriesController extends FrontController
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('board.public.category')
            ->with('topbox', 'category')
            ->with('category', $category)
            ->with('self', $category)
            ->with('mods', $category->moderators())
            ->with('current_board', $category->board()->firstOrFail());
    }
}
