<?php

namespace App\Http\Controllers\Website;

use Activity;
use App\Post;
use App\User;
use App\Topic;
use App\Directory;

class WebsiteController
{
    public function index()
    {
        $refresh_online_minutes = config('custom.refresh_online_minutes');

        $visible_online = Activity::users($refresh_online_minutes)
            ->join('users', 'sessions.user_id', 'users.id')
            ->where('is_invisible', false)
            ->get();

        $guest_count = Activity::guests()->count();
        $visible_online_count = $visible_online->count();
        $all_online_count = Activity::users($refresh_online_minutes)->count();

        return view('website.index')
            ->with('post_count', Post::count())
            ->with('guest_count', $guest_count)
            ->with('topic_count', Topic::count())
            ->with('directories', Directory::all())
            ->with('newest_user', User::newest_user())
            ->with('user_count', User::all()->count())
            ->with('visible_online', $visible_online)
            ->with('visible_online_count', $visible_online_count)
            ->with('refresh_online_minutes', $refresh_online_minutes)
            ->with('people_online', $all_online_count + $guest_count)
            ->with('invisible_online_count', $all_online_count - $visible_online_count);
    }
}
