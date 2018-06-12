<?php

namespace App\Http\Controllers\Front;

use App\Category;

class CategoriesController extends FrontController
{
    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        return view('board.public.category')
            ->with('topbox', 'category')
            ->with('category', $category)
            ->with('self', $category)
            ->with('mods', $category->moderators());
    }
}
