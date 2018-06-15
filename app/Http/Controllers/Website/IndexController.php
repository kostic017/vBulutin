<?php

namespace App\Http\Controllers\Website;

use App\Board;
use App\BoardCategory;

class IndexController extends WebsiteController
{
    public function index($slug = "all") {
        if ($slug === "all") {
            $boards = Board::all();
        } else {
            $boards = BoardCategory::where('slug', $slug)->firstOrFail()->boards()->get();
        }

        return view('website.index')
            ->with('boardCategories', BoardCategory::all())
            ->with('boards', $boards);
    }
}
