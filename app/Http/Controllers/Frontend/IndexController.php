<?php

namespace App\Http\Controllers\Frontend;

use Validator;

use App\User;
use App\Category;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class IndexController extends DashboardController
{
    public function index(): View
    {
        return view('public.index')->with('categories', Category::all());
    }

    /**
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function users(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Get Valid or Correct Bad Input
        |--------------------------------------------------------------------------
        */

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
            ->select('username', 'is_banned', 'registered_at', 'about', DB::raw('COUNT(*) AS post_count'))
            ->join('profiles', 'users.id', 'profiles.user_id')
            ->join('posts', 'users.id', 'posts.user_id')
            ->groupBy('username', 'is_banned', 'registered_at', 'about')
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
}
