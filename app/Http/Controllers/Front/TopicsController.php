<?php

namespace App\Http\Controllers\Front;

use Auth;
use Validator;
use App\Post;
use App\Forum;
use App\Topic;
use App\Category;
use Illuminate\Http\Request;

class TopicsController extends FrontController
{

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug)
    {
        $topic = Topic::where('slug', $slug)->firstOrFail();
        $forum = Forum::findOrFail($topic->forum_id);
        $category = Category::findOrFail($forum->category_id);

        $posts = (is_admin() ? Post::withTrashed() : Post::query())
                ->where('topic_id', $topic->id)->orderBy('created_at', 'asc')
                ->get();

        $vars = [
            'topbox' => 'topic',
            'self' => $topic,
            'category' => $category,
            'forum' => $forum,
            'lastPost' => $topic->lastPost(),
            'topicStarter' => $topic->starter(),
            'solution' => $topic->solution(),
            'posts' => $posts,
        ];

        if ($forum->parent_id) {
            $vars['parent'] = Forum::findOrFail($forum->parent_id);
        }

        return view('public.topic')->with($vars);
    }

    /**
     * Toggle lock state of the specified resource.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function lock($id)
    {
        $topic = Topic::findOrFail($id);
        $topic->is_locked = !$topic->is_locked;
        $topic->save();
        return redirect()->back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $forum_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
        $post->user_id = Auth::id();
        $post->save();

        return redirect(route('front.topics.show', ['topic' => $topic->slug]));
    }

    /**
     * Update title of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTitle(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
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

    /**
     * Update solution of this topic.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateSolution(Request $request, string $id)
    {
        $topic = Topic::findOrFail($id);
        $topic->solution_id = $request->solution_id;
        $topic->save();

        return redirect()->back();
    }
}
