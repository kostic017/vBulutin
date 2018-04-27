<?php

namespace App\Http\Controllers;

use App\User;
use App\Post;
use App\Forum;
use App\Topic;
use App\Category;
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
            error('404');
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
                'topics' => $forum->topics()->get(),
                'children' => $forum->children()->get(),
                'category' => Category::findOrFail($forum->category_id),
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.forum')->with($vars);
        } catch (ModelNotFoundException $e) {
            error('404');
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
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.topic')->with($vars);
        } catch (ModelNotFoundException $e) {
            error('404');
        }
    }

    public function user(string $username) {
        try {
            return view('public.user')->with('user', User::where('username', $username)->firstOrFail());
        } catch (ModelNotFoundException $e) {
            error('404');
        }
    }

}
