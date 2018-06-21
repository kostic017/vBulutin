<?php

namespace App\Http\Controllers\Board\Publicus;

use View;
use Activity;
use App\User;
use App\Post;
use App\Topic;
use App\Http\Controllers\Controller;

class PublicusController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $refresh_online_minutes = config('custom.refresh_online_minutes');

            $visible_online = Activity::users($refresh_online_minutes)
                ->join('users', 'sessions.user_id', 'users.id')
                ->where('is_invisible', false)
                ->get();

            $guest_count = Activity::guests()->count();
            $visible_online_count = $visible_online->count();
            $all_online_count = Activity::users($refresh_online_minutes)->count();

            View::share('post_count', Post::count());
            View::share('guest_count', $guest_count);
            View::share('topic_count', Topic::count());
            View::share('newest_user', User::newest_user());
            View::share('user_count', User::all()->count());
            View::share('visible_online', $visible_online);
            View::share('refresh_online_minutes', $refresh_online_minutes);
            View::share('people_online', $all_online_count + $guest_count);
            View::share('visible_online_count', $visible_online_count);
            View::share('invisible_online_count', $all_online_count - $visible_online_count);

            return $next($request);
        });
    }
}
