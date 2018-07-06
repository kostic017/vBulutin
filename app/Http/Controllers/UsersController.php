<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Hash;
use Validator;

use App\Post;
use App\User;
use App\Topic;

use Illuminate\Validation\Rule;

class UsersController extends Controller {

    public function index() {
        $request = request();
        $query = User::query();

        /*
        |--------------------------------------------------------------------------
        | Default Values
        |--------------------------------------------------------------------------
        */

        $columns = [
            'id' => true,
            'username' => true,
            'registered_at' => true,
            'name' => false,
            'sex' => true,
            'birthday_on' => false,
            'birthplace' => false,
            'residence' => false,
            'job' => false,
            'post_count' => true,
            'about' => false,
            'signature' => false,
        ];

        $pagination_max = (int)config('custom.pagination.max');
        $pagination_step = (int)config('custom.pagination.step');
        $per_page = $pagination_step;

        $users = User::all();
        return view('website.users.index')->with(compact(
            'users', 'per_page', 'pagination_step', 'pagination_max', 'columns'
        ));
    }

    public function index_admin($board_address) {
        return view('website.users.index')->with('users', User::all());
    }

    public function show($username) {
        if (Auth::check()) {
            $user = User::where('username', $username)->firstOrFail();
            return view('website.users.show')
                ->with('user', $user)
                ->with('profile', $user->profile);
        } else {
            return alert_redirect(url()->previous(), 'info', __('auth.must-login'));
        }
    }

    public function edit($username) {
        if (Auth::check()) {
            $user = User::where('username', $username)->firstOrFail();
            if (Auth::id() == $user->id || Auth::user()->is_master) {
                return view('website.users.edit')
                    ->with('user', $user)
                    ->with('profile', $user->profile);
            }
            return redirect(route_user_show($user));
        } else {
            return alert_redirect(route(url()->previous()), 'info', __('auth.must-login'));
        }
    }

    public function update($username) {
        $errors = [];
        $request = request();

        $user = User::where('username', $username)->firstOrFail();
        $profile = $user->profile;

        $user->is_invisible = $request->is_invisible;

        if ($user->email !== $request->email || is_not_empty($request->password) || is_not_empty($request->password_confirm)) {
            if (is_empty($request->password_current)) {
                $errors['password_current'] = 'Niste upisali trenutnu šifru.';
            } elseif (!Hash::check($request->password_current, $user->password)) {
                $errors['password_current'] = 'Upisali ste pogrešnu šifru.';
            } else {
                if ($user->email !== $request->email) {
                    $user->to_logout = true;
                    $user->email = $request->email;
                    $user->email_token = str_random(30);
                    $user->notify(new ConfirmEmail($user->email_token));
                }

                if (is_not_empty($request->password) || is_not_empty($request->password_confirm)) {
                    if ($request->password !== $request->password_confirm) {
                        $errors['password_matching'] = 'Lozinke se ne poklapaju.';
                    } elseif (!isset($errors['password_current'])) {
                        $user->password = Hash::make($request->password);
                        $user->to_logout = true;
                    }
                }
            }
        }

        if (!empty($errors)) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        $profile->about = $request->about;
        $profile->birthday_on = $request->birthday_on;
        $profile->sex = $request->sex;
        $profile->job = $request->job;
        $profile->name = $request->name;
        $profile->residence = $request->residence;
        $profile->birthplace = $request->birthplace;
        $profile->avatar = $request->avatar;
        $profile->signature = $request->signature;

        $user->save();
        $profile->save();

        return alert_redirect(route_user_show($user), 'success', __('db.updated'));
    }

    public function ban($username) {
        // $user = User::where('username', $username)->firstOrFail();
        // $user->is_banned = !$user->is_banned;
        // $user->save();
        // return redirect()->back();
    }

}
