<?php

namespace App\Http\Controllers\Frontend;

use Auth;

use App\User;

use Illuminate\Http\RedirectResponse;

class EditController extends DashboardController
{
    public function profile(string $profile): RedirectResponse
    {
        if (Auth::check()) {
            try {
                $user = User::where('username', $profile)->firstOrFail();
                if (Auth::id() == $user->id || Auth::user()->is_admin) {
                    return view('public.editprofile')
                        ->with('user', $user)
                        ->with('profile', $user->profile()->firstOrFail());
                }
                return redirect(route('public.profile.show', ['profile' => $profile]));
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
}
