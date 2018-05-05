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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function __construct()
    {
        view()->share('newestUser', User::newestUser());
        view()->share('userCount', User::count());
        view()->share('postCount', Post::count());
        view()->share('topicCount', Topic::count());
    }

    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        return view('public.index')->with('categories', Category::all());
    }

    public function users(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Valid or Correct Bad Input
        |--------------------------------------------------------------------------
        */

        $request = request();
        $cols = ['username', 'about', 'registered_at', 'post_count'];
        $max = (int)config('custom.pagination.max');
        $step = (int)config('custom.pagination.step');

        $validator = Validator::make($request->all(), [
            'perPage' => "integer|between:0,{$max}",
            'sort_column' => Rule::in($cols),
            'sort_order' => Rule::in(['asc', 'desc']),
            'search_column' => Rule::in($cols),
        ]);

        $errors = $validator->errors();

        $perPage =  $request->has('perPage') && !$errors->has('perPage') ? (int)$request->perPage : $step;
        $sortColumn = $request->has('sort_column') && !$errors->has('sort_column') ? $request->sort_column : 'username';
        $sortOrder = $request->has('sort_order') && !$errors->has('sort_order') ? $request->sort_order : 'asc';
        $searchColumn = $request->has('search_column') && !$errors->has('search_column') ? $request->search_column : 'title';

        if ($remainder = $perPage % $step) {
            $perPage = ($perPage - $remainder) ?: $step;
            $errors->add('perPage', '');
        }

        if ($errors->any()) {
            $queries = [
                'perPage' => $perPage,
                'sort_column' => $sortColumn,
                'sort_order' => $sortOrder,
            ];

            return redirect(request()->fullUrlWithQuery($queries));
        }

        /*
        |--------------------------------------------------------------------------
        | Build Query And Fetch Data
        |--------------------------------------------------------------------------
        */

        $users = User::query()
            ->select('username', 'registered_at', 'about', DB::raw('COUNT(*) AS post_count'))
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->join('posts', 'users.id', 'posts.user_id')
            ->groupBy('username', 'registered_at', 'about')
            ->orderBy($sortColumn, $sortOrder);

        if ($perPage) {
            $users = $users->paginate($perPage);
        } else {
            $users = $users->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Return Response
        |--------------------------------------------------------------------------
        */

        return view('public.users')
            ->with(compact('users', 'perPage', 'step', 'max', 'sortColumn', 'sortOrder'));
    }

    /*
    |--------------------------------------------------------------------------
    | Show
    |--------------------------------------------------------------------------
    */

    public function showProfile(string $profile)
    {
        try {
            $user = User::where('username', $profile)->firstOrFail();
            return view('public.showprofile')
                ->with('user', $user)
                ->with('profile', $user->profile()->firstOrFail());
        } catch (Exception $e) {
            abort('404');
        }
    }

    public function category(string $category)
    {
        try {
            $category = Category::where('slug', $category)->firstOrFail();
            return view('public.category')
                ->with('topbox', 'category')
                ->with('self', $category);
        } catch (Exception $e) {
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
                'children' => $forum->children()->get(),
                'topics' => $forum->topics()->orderBy('updated_at', 'desc')->get(),
                'category' => Category::findOrFail($forum->category_id),
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.forum')->with($vars);
        } catch (Exception $e) {
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
                'posts' => $topic->posts()->orderBy('created_at', 'asc')->get()
            ];

            if ($forum->parent_id) {
                $vars['parent'] = Forum::findOrFail($forum->parent_id);
            }

            return view('public.topic')->with($vars);
        } catch (Exception $e) {
            abort('404');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

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

            $topic->touch();

            return redirect(route('public.topic', ['topic' => $topic->slug]) . '#post-' . $post->id);
        } catch (ModelNotFoundException $e) {
            throw new SomeException($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function editProfile(string $profile)
    {
        try {
            $user = User::where('username', $profile)->firstOrFail();
            return view('public.editprofile')
                ->with('user', $user)
                ->with('profile', $user->profile()->firstOrFail());
        } catch (Exception $e) {
            abort('404');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updateProfile(string $profile)
    {

    }

}
