<?php

namespace App\Http\Controllers\Frontend;

use Activity;

use App\User;
use App\Post;
use App\Topic;

use App\Http\Controllers\Controller;

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
}
