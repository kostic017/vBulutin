<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Post;
use App\Board;
use App\Forum;
use App\Topic;

class TopicsController
{

    public function show($board_url, $category_slug, $forum_slug, $topic_slug)
    {
        $board = Board::where('url', $board_url)->firstOrFail();
        $category = $board->categories()->where('slug', $category_slug)->firstOrFail();
        $forum = $category->forums()->where('slug', $forum_slug)->firstOrFail();
        $topic = $forum->topics()->where('slug', $topic_slug)->firstOrFail();
        $is_admin = $board->is_admin();

        $posts = ($is_admin ? Post::withTrashed() : Post::query())
                ->where('topic_id', $topic->id)->orderBy('created_at', 'asc')
                ->get();

        $vars = [
            'topic' => $topic,
            'forum' => $forum,
            'posts' => $posts,
            'category' => $category,
            'is_admin' => $is_admin,
            'current_board' => $board,
            'solution' => $topic->solution(),
            'lastPost' => $topic->lastPost(),
            'topic_starter' => $topic->starter(),
            'parent_forum' => $forum->parent()->first(),
        ];

        if ($forum->parent_id) {
            $vars['parent'] = Forum::findOrFail($forum->parent_id);
        }

        return view('public.topic')->with($vars);
    }

    public function lock($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->is_locked = !$topic->is_locked;
        $topic->save();
        return redirect()->back();
    }

    public function store()
    {
        $request = request();

        $validator = \Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous(). '#scform')->withErrors($validator)->withInput();
        }

        $topic = new Topic;
        $topic->title = $request->title;
        $topic->slug = str_slug($topic->title);
        $topic->forum_id = $request->forum_id;
        $topic->save();

        $topic->slug = unique_slug($topic->title, $topic->id);
        $topic->save();

        $post = new Post;
        $post->content = $request->content;
        $post->topic_id = $topic->id;
        $post->user_id = \Auth::id();
        $post->save();

        return redirect(route_topic_show($topic));
    }

    public function update_title($id)
    {
        $request = request();

        $validator = \Validator::make($request->all(), [
            'title' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $topic = Topic::findOrFail($id);
        $topic->title = $request->title;
        $topic->slug = unique_slug($topic->title, $topic->id);
        $topic->save();

        return redirect()->back();
    }

    public function update_solution($id)
    {
        $request = request();

        $topic = Topic::findOrFail($id);
        $topic->solution_id = $request->solution_id;
        $topic->save();

        return redirect()->back();
    }
}
