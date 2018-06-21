<?php

namespace App\Http\Controllers\Board\Admin;

use App\User;

class UsersController
{
    public function ban($username)
    {
        $user = User::where('username', $username)->firstOrFail();
        $user->is_banned = !$user->is_banned;
        $user->save();
        return redirect()->back();
    }
}
