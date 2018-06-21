<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Forum;
use App\Category;

class ForumsController extends PublicusController
{
    public function show($slug)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();
        $category = $forum->category()->firstOrFail();
        $board = $category->board()->firstOrFail();

        $vars = [
            'self' => $forum,
            'category' => $category,
            'current_board' => $board,
            'is_admin' => $board->is_admin(),
            'children' => $forum->children()->get(),
            'topics' => $forum->topics()->orderBy('updated_at', 'desc')->get(),
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



