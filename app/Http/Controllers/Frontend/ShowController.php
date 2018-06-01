<?php

namespace App\Http\Controllers\Frontend;

use Auth;

use App\User;
use App\Forum;
use App\Topic;
use App\Category;

use Illuminate\View\View;

class ShowController extends DashboardController
{

    /**
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function profile(string $username)
    {
        if (Auth::check()) {
            try {
                $user = User::where('username', $username)->firstOrFail();
                return view('public.showprofile')
                    ->with('user', $user)
                    ->with('profile', $user->profile()->firstOrFail());
            } catch (Exception $e) {
                abort('404');
            }
        } else {
            return redirect('/')->with([
                'alert-type' => 'info',
                'message' => 'Morate biti prijavljeni da bi videli ovu stranicu'
            ]);
        }
    }

    public function category(string $categorySlug): View
    {
        try {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            return view('public.category')
                ->with('topbox', 'category')
                ->with('category', $category)
                ->with('self', $category)
                ->with('mods', $category->moderators());
        } catch (Exception $e) {
            abort('404');
        }
    }

    public function forum(string $forumSlug): View
    {
        try {
            $forum = Forum::where('slug', $forumSlug)->firstOrFail();

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
        } catch (Exception $e) {
            abort('404');
        }
    }

    public function topic(string $topicSlug): View
    {
        try {
            $topic = Topic::where('slug', $topicSlug)->firstOrFail();
            $forum = Forum::findOrFail($topic->forum_id);
            $category = Category::findOrFail($forum->category_id);

            $vars = [
                'topbox' => 'topic',
                'self' => $topic,
                'category' => $category,
                'forum' => $forum,
                'lastPost' => $topic->lastPost(),
                'topicStarter' => $topic->starter(),
                'solution' => $topic->solution(),
                'posts' => $topic->posts()->orderBy('created_at', 'asc')->get(),
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.topic')->with($vars);
        } catch (Exception $e) {
            abort('404');
        }
    }
}
