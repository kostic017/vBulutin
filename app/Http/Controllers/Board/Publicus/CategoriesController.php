<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Category;

class CategoriesController extends PublicusController
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('public.category')
            ->with('topbox', 'category')
            ->with('category', $category)
            ->with('self', $category)
            ->with('current_board', $category->board()->firstOrFail());
    }
}
