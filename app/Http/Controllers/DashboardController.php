<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('public.index')->with('categories', $categories);
    }
}
