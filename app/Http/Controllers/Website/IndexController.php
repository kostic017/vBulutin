<?php

namespace App\Http\Controllers\Website;

use App\Board;
use App\BoardCategory;

class IndexController extends WebsiteController
{
    public function index($category_slug) {
        if ($category_slug === "all") {
            $boards = Board::all();
        } else {
            $boards = BoardCategory::where('slug', $category_slug)->firstOrFail()->boards()->get();
        }

        return view('website.index')->with('boards', $boards);
    }
}
