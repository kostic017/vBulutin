<?php

namespace App\Http\Controllers;

use Validator;

use App\Board;
use App\Forum;
use App\Category;

class CategoriesController extends SectionsController {
    protected $table = 'categories';
    protected $singular = 'category';
    protected $model = 'App\Category';

    public function edit($board_address, $slug) {
        return view('admin.sections.categories.edit')->with(
            'category', get_board($board_address)->categories()->where('categories.slug', $slug)->firstOrFail()
        );
    }

    public function destroy($board_address, $id) {
        return parent::destroy($board_address, $id);
    }

    public function restore($board_address, $id) {
        return parent::restore($board_address, $id);
    }

    public function create($board_address) {
        return view('admin.sections.categories.create');
    }

    public function store($board_address) {
        $request = request();
        $board = get_board($board_address);

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($board) {
                    if ($board->categories()->where('categories.title', $value)->count()) {
                        return $fail(trans('validation.unique', ['attribute' => $attribute]));
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = new Category;
        $category->title = $request->title;
        $category->slug = str_slug($category->title);
        $category->description = $request->description;
        $category->position = $board->categories()->max('position') + 1;
        $category->board_id = $board->id;
        $category->save();

        return alert_redirect(route('categories.show.admin', [$board_address, $category->slug]), 'success', __('db.stored'));
    }

    public function update($board_address, $id) {
        $request = request();
        $board = get_board($board_address);

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($board, $id) {
                    $collision = $board->categories()->where('categories.title', $value)->first();
                    if ($collision && $collision->id !== (int)$id) {
                        return $fail(trans('validation.unique', ['attribute' => $attribute]));
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = Category::findOrFail($id);
        $category->title = $request->title;
        $category->slug = str_slug($category->title);
        $category->description = $request->description;
        $category->save();

        return alert_redirect(route("categories.show.admin", [request()->route('board_address'), $category->slug]), 'success', __('db.updated'));
    }

    public function show_admin($board_address, $category_slug) {
        $category = get_board($board_address)->categories()->withTrashed()->where('categories.slug', $category_slug)->firstOrFail();
        return view('admin.sections.categories.show')->with('category', $category);
    }

    public function show($board_address, $category_slug) {
        $board = get_board($board_address);
        $category = $board->categories()->where('slug', $category_slug)->firstOrFail();

        return view('public.category')
            ->with('category', $category)
            ->with('current_board', $board)
            ->with('is_admin', $board->is_admin());
    }

}
