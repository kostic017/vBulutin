<?php

namespace App\Http\Controllers;

use Auth;
use Hash;
use Activity;
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
        view()->share('postCount', Post::count());
        view()->share('topicCount', Topic::count());
        view()->share('newestUser', User::newestUser());
        view()->share('userCount', User::all()->count());

        $onlineUsersMinutes = config('custom.online_users_minutes');

        $visibleOnlineUsers = Activity::users($onlineUsersMinutes)
            ->join('users', 'sessions.user_id', 'users.id')
            ->where('is_invisible', false)
            ->get();

        $guestsCount = Activity::guests()->count();
        $visibleOnlineUsersCount = $visibleOnlineUsers->count();
        $allOnlineUsersCount = Activity::users($onlineUsersMinutes)->count();

        view()->share('guestsCount', $guestsCount);
        view()->share('visibleOnlineUsers', $visibleOnlineUsers);
        view()->share('onlineUsersMinutes', $onlineUsersMinutes);
        view()->share('peopleOnline', $allOnlineUsersCount + $guestsCount);
        view()->share('visibleOnlineUsersCount', $visibleOnlineUsersCount);
        view()->share('invisibleOnlineUsersCount', $allOnlineUsersCount - $visibleOnlineUsersCount);
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

    public function showProfile(string $username)
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

    public function category(string $categorySlug)
    {
        try {
            $category = Category::where('slug', $categorySlug)->firstOrFail();
            return view('public.category')
                ->with('topbox', 'category')
                ->with('self', $category);
        } catch (Exception $e) {
            abort('404');
        }
    }

    public function forum(string $forumSlug)
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

            return view('public.forum')->with($vars);
        } catch (Exception $e) {
            abort('404');
        }
    }

    public function topic(string $topicSlug) {
        try {
            $topic = Topic::where('slug', $topicSlug)->firstOrFail();
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

    public function createTopic(Request $request, string $forumId)
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
        $topic->slug = str_slug($topic->title);
        $topic->forum_id = $forumId;
        $topic->save();

        $topic->slug = unique_slug($topic->title, $topic->id);
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
            throw new UnexpectedException($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function editProfile(string $profile)
    {
        if (Auth::check()) {
            try {
                $user = User::where('username', $profile)->firstOrFail();
                $view = Auth::id() == $user->id ? 'public.editprofile' : 'public.showprofile';
                return view($view)
                    ->with('user', $user)
                    ->with('profile', $user->profile()->firstOrFail());
            } catch (Exception $e) {
                abort('404');
            }
        } else {
            return redirect('/')->with([
                'alert-type' => 'info',
                'message' => 'Morate biti prijavljeni da bi videli ovu stranicu.'
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function updateProfile(Request $request, string $profile)
    {
        try {
            $errors = [];

            $user = Users::where('username', $profile)->firstOrFail();
            $profile = $user->profile()->get();

            $user->is_invisible = $request->is_invisible;

            if ($user->email !== $request->email || isNotEmpty($request->password) || isNotEmpty($request->password_confirm)) {
                if (isEmpty($request->password_current)) {
                    $errors['password_current'] = 'Niste upisali trenutnu šifru.';
                } elseif (Hash::check($request->password, $user->password)) {
                    $errors['password_current'] = 'Upisali ste pogrešnu šifru.';
                }

                if ($user->email !== $request->email) {
                    $user->is_confirmed = false;
                    $user->email = $request->email;
                    $user->email_token = str_random(30);
                    $user->notify(new ConfirmEmail($user->email_token));
                    $user->to_logout = true;
                }

                if (isNotEmpty($request->password) || isNotEmpty($request->password_confirm)) {
                    if ($request->password !== $request->password_confirm) {
                        $errors['password_matching'] = 'Lozinke se ne poklapaju.';
                    } elseif (!isset($errors['password_current'])) {
                        $user->password = Hash::make($request->password);
                        $user->to_logout = true;
                    }
                }
            }

            if (!empty($errors)) {
                return redirect()->back()->with('errors', $errors)->withInput();
            }

            $profile->about = $request->about;
            $profile->birthday_on = $request->birthday_on;
            $profile->sex = $request->sex;
            $profile->job = $request->job;
            $profile->name = $request->name;
            $profile->residence = $request->residence;
            $profile->birthplace = $request->birthplace;
            $profile->avatar = $request->avatar;

            $user->save();
            $profile->save();
            return redirect(route('public.profile.show', ['profile' => $user->username]))->with([
                'alert-type' => 'success',
                'message' => 'Podaci uspešno izmenjeni.'
            ]);
        } catch (ModelNotFoundException $e) {
            return redirect('/');
        }
    }

}
