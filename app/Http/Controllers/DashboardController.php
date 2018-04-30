<?php

namespace App\Http\Controllers;

use Auth;
use Validator;

use App\User;
use App\Post;
use App\Forum;
use App\Topic;
use App\Category;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DashboardController extends Controller
{

    public function __construct()
    {
        view()->share('newestUser', User::newestUser());
        view()->share('userCount', User::count());
        view()->share('postCount', Post::count());
        view()->share('topicCount', Topic::count());
    }

    public function index()
    {
        $categories = Category::all();
        return view('public.index')->with('categories', $categories);
    }

    public function category(string $category)
    {
        try {
            $category = Category::where('slug', $category)->firstOrFail();
            return view('public.category')
                ->with('topbox', 'category')
                ->with('self', $category)
                ->with('category', $category);
        } catch (ModelNotFoundException $e) {
            abort('404');
        }
    }

    public function forum(string $forum)
    {
        try {
            $forum = Forum::where('slug', $forum)->firstOrFail();

            $vars = [
                'topbox' => 'forum',
                'self' => $forum,
                'forum' => $forum,
                'children' => $forum->children()->get(),
                'topics' => $forum->topics()->orderBy('updated_at', 'desc')->get(),
                'category' => Category::findOrFail($forum->category_id),
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.forum')->with($vars);
        } catch (ModelNotFoundException $e) {
            abort('404');
        }
    }

    public function topic(string $topic) {
        try {
            $topic = Topic::where('slug', $topic)->firstOrFail();
            $forum = Forum::findOrFail($topic->forum_id);
            $category = Category::findOrFail($forum->category_id);

            $vars = [
                'topbox' => 'topic',
                'self' => $topic,
                'category' => $category,
                'forum' => $forum,
                'topic' => $topic,
                'posts' => $topic->posts()->orderBy('created_at', 'asc')->get()
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.topic')->with($vars);
        } catch (ModelNotFoundException $e) {
            abort('404');
        }
    }

    public function user(string $username) {
        try {
            return view('public.user')->with('user', User::where('username', $username)->firstOrFail());
        } catch (ModelNotFoundException $e) {
            abort('404');
        }
    }

    public function createTopic(Request $request, string $forum)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $topic = new Topic;
        $topic->title = $request->title;
        $topic->forum_id = $forum;
        $topic->save();

        $post = new Post;
        $post->content = e($request->content);
        $post->topic_id = $topic->id;
        $post->user_id = Auth::id();
        $post->save();

        return redirect(route('public.topic', ['topic' => $topic->slug]));
    }

    public function createPost(Request $request, string $topic)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $topic = Topic::findOrFail($topic);

            $post = new Post;
            $post->content = e($request->content);
            $post->topic_id = $topic->id;
            $post->user_id = Auth::id();
            $post->save();

            return redirect(route('public.topic', ['topic' => $topic->slug]) . '#post-' . $post->id);
        } catch (ModelNotFoundException $e) {
            throw new SomeException($e);
        }

    }

}
