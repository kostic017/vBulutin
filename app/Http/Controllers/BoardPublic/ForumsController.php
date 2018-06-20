<?php

namespace App\Http\Controllers\BoardPublic;

use App\Forum;
use App\Category;

class ForumsController extends FrontController
{
    public function show($slug)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();
        $category = $forum->category()->firstOrFail();

        $vars = [
            'topbox' => 'forum',
            'self' => $forum,
            'children' => $forum->children()->get(),
            'topics' => $forum->topics()->orderBy('updated_at', 'desc')->get(),
            'category' => $category,
            'current_board' => $category->board()->firstOrFail(),
        ];

        if ($forum->parent_id) {
            $vars['parent'] = $forum->parent()->firstOrFail();
        }

        return view('public.forum')->with($vars);
    }

    public function lock($id)
    {
        $forum = Forum::findOrFail($id);
        $forum->is_locked = !$forum->is_locked;
        $forum->save();
        return redirect()->back();
    }
}



