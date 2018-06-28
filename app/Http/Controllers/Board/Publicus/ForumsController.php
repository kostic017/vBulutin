<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Board;
use App\Forum;

class ForumsController
{
    public function show($board_address, $forum_slug)
    {
        $board = Board::where('address', $board_address)->firstOrFail();
        $forum = $board->forums()->where('forums.slug', $forum_slug)->firstOrFail();

        $vars = [
            'forum' => $forum,
            'current_board' => $board,
            'category' => $forum->category,
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



