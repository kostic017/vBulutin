<?php

namespace App\Http\Controllers\Website;

use View;
use App\BoardCategory;

class WebsiteController
{
    public function __construct()
    {
        View::share('boardCategories', BoardCategory::all());
    }
}
