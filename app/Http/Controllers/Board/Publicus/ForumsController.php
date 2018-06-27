<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Board;
use App\Forum;

class ForumsController
{
    public function show($board_url, $category_slug, $forum_slug)
    {
        $board = Board::where('url', $board_url)->firstOrFail();
        $category = $board->categories()->where('slug', $category_slug)->firstOrFail();
        $forum = $category->forums()->where('slug', $forum_slug)->firstOrFail();

        $vars = [
            'forum' => $forum,
            'category' => $category,
            'current_board' => $board,
            'is_admin' => $board->is_admin(),
            'child_forums' => $forum->children()->get(),
            'topics' => $forum->topics()->orderBy('updated_at', 'desc')->get(),
        ];

        if ($forum->parent_id) {
            $vars['parent_forum'] = $forum->parent;
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



