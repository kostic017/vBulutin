<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Validator;

use App\User;
use App\Topic;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class EditController extends DashboardController
{
    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $username
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function profile(string $username)
    {
        if (Auth::check()) {
            try {
                $user = User::where('username', $username)->firstOrFail();
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

    public function topicTitle(Request $request, string $topicSlug): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $topic = Topic::where('slug', $topicSlug)->firstOrFail();
        $topic->title = $request->title;
        $topic->slug = unique_slug($topic->title, $topic->id);
        $topic->save();

        return redirect(route('public.topic', ['topic' => $topic->slug]));
    }

    public function topicSolution(Request $request, string $topicSlug): RedirectResponse
    {
        $topic = Topic::where('slug', $topicSlug)->firstOrFail();
        $topic->solution_id = $request->solution_id;
        $topic->save();

        return redirect()->back();
    }
}
