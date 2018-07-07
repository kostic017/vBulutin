<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Hash;
use Validator;

use App\Post;
use App\User;
use App\Topic;

class UsersController extends Controller {

    public function index() {
        $request = request();

        $pagination_max = (int)config('custom.pagination.max');
        $pagination_step = (int)config('custom.pagination.step');

        $show_columns = [
            'id' => false,
            'username' => false,
            'registered_at' => false,
            'name' => false,
            'sex' => false,
            'birthday_on' => false,
            'birthplace' => false,
            'residence' => false,
            'job' => false,
            'post_count' => false,
            'about' => false,
            'signature' => false,
        ];

        if ($request->has('show_columns')) {
            if (count($request->show_columns)) {
                foreach ($request->show_columns as $column)
                    $show_columns[$column] = true;
            } else {
                $show_columns['username'] = true;
            }
        } else {
            $show_columns['id'] = true;
            $show_columns['username'] = true;
            $show_columns['registered_at'] = true;
            $show_columns['post_count'] = true;
        }

        $user_group = $request->input('user_group', 'all');
        $sort_order = $request->input('sort_order', 'asc');
        $search_query = $request->input('search_query', '');
        $user_status = $request->input('user_status', 'all');
        $per_page = $request->input('per_page', $pagination_step);
        $sort_column = $request->input('sort_column', first_true_key($show_columns));
        $search_field = $request->input('search_field', first_true_key($show_columns));

        $query = User::select();

        if ($user_status !== 'all')
            $query->{$user_status}();
        if ($user_group !== 'all')
            $query->{$user_group}();
        if (is_not_empty($search_query))
            $query->where($search_field, 'like', "%$search_query%");
        $query->orderBy($sort_column, $sort_order);

        $users = $per_page > 0 ? $query->paginate($per_page) : $query->get();

        return view('website.users.index')->with(compact(
            'users',
            'per_page',
            'pagination_step',
            'pagination_max',
            'search_query',
            'search_field',
            'user_status',
            'user_group',
            'show_columns',
            'sort_column',
            'sort_order'
        ));
    }

    public function index_admin($board_address) {
        return view('website.users.index')->with('users', User::all());
    }

    public function show($username) {
        if (Auth::check()) {
            $user = User::where('username', $username)->firstOrFail();
            return view('website.users.show')->with('user', $user);
        } else {
            return alert_redirect(url()->previous(), 'info', __('auth.must-login'));
        }
    }

    public function edit($username) {
        if (Auth::check()) {
            $user = User::where('username', $username)->firstOrFail();
            if (Auth::id() == $user->id || Auth::user()->is_master) {
                return view('website.users.edit')->with('user', $user);
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

        $user->about = $request->about;
        $user->birthday_on = $request->birthday_on;
        $user->sex = $request->sex;
        $user->job = $request->job;
        $user->name = $request->name;
        $user->residence = $request->residence;
        $user->birthplace = $request->birthplace;
        $user->avatar = $request->avatar;
        $user->signature = $request->signature;

        $user->save();

        return alert_redirect(route_user_show($user), 'success', __('db.updated'));
    }

    public function ban($username) {
        // $user = User::where('username', $username)->firstOrFail();
        // $user->is_banned = !$user->is_banned;
        // $user->save();
        // return redirect()->back();
    }

}
