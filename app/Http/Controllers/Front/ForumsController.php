<?php

namespace App\Http\Controllers\Front;

use App\Forum;
use App\Category;

class ForumsController extends FrontController
{
    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $forum = Forum::where('slug', $slug)->firstOrFail();

        $vars = [
            'topbox' => 'forum',
            'self' => $forum,
            'children' => $forum->children()->get(),
            'topics' => $forum->topics()->orderBy('updated_at', 'desc')->get(),
            'category' => Category::findOrFail($forum->category_id),
        ];

        if ($forum->parent_id) {
            $vars['parent'] = Forum::findOrFail($forum->parent_id);
        }

        return view('public.forum')
            ->with($vars)
            ->with('mods', $forum->moderators());
    }

    /**
     * Toggle lock state of the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lock($id)
    {
        $forum = Forum::findOrFail($id);
        $forum->is_locked = !$forum->is_locked;
        $forum->save();
        return redirect()->back();
    }
}



