<?php

namespace App\Http\Controllers;

use Activity;
use App\Post;
use App\User;
use App\Topic;
use App\Directory;

class WebsiteController extends Controller {
    public function index() {
        $vars = [
            'directories' => Directory::all()
        ];

        if (User::count() > 0) {
            $refresh_online_minutes = config('custom.refresh_online_minutes');

            $visible_online = Activity::users($refresh_online_minutes)
                ->join('users', 'sessions.user_id', 'users.id')
                ->where('is_invisible', false)
                ->get();

            $guest_count = Activity::guests()->count();
            $visible_online_count = $visible_online->count();
            $all_online_count = Activity::users($refresh_online_minutes)->count();

            $vars['show_stats'] = true;
            $vars['post_count'] = Post::count();
            $vars['guest_count'] = $guest_count;
            $vars['topic_count'] = Topic::count();
            $vars['newest_user'] = User::newest_user();
            $vars['user_count'] = User::all()->count();
            $vars['visible_online'] = $visible_online;
            $vars['visible_online_count'] = $visible_online_count;
            $vars['refresh_online_minutes'] = $refresh_online_minutes;
            $vars['people_online_count'] = $all_online_count + $guest_count;
            $vars['invisible_online_count'] = $all_online_count - $visible_online_count;
        }

        return view('website.index')->with($vars);
    }
}
