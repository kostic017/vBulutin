<?php

namespace App\Http\Controllers\Board\Admin;

use App\Forum;
use App\Category;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriesController extends SectionsController
{
    protected $table = 'categories';
    protected $singular = 'category';
    protected $model = 'App\Category';

    public function index()
    {
        return parent::index();
    }

    public function edit($slug)
    {
        return parent::edit($slug);
    }

    public function update($request, $id)
    {
        return parent::update($request, $id);
    }

    public function destroy($id)
    {
        return parent::destroy($id);
    }

    public function restore($id)
    {
        return parent::restore($id);
    }

    public function create()
    {
        return view('admin.sections.categories.create');
    }

    public function store($request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|max:255|unique:categories'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = new Category;
        $category->title = $request->title;
        $category->slug = str_slug($category->title);
        $category->description = $request->description;
        $category->position = Category::max('position') + 1;
        $category->save();

        $category->slug = unique_slug($category->title, $category->id);
        $category->save();

        return alert_redirect(route('admin.categories.show', ['category' => $category->slug]), 'success', __('db.stored'));
    }

    public function show($slug)
    {
        $category = Category::withTrashed()->where('slug', $slug)->firstOrFail();
        return view('admin.sections.categories.show')->with('category', $category);
    }

    public function positions()
    {
        $columns = ['id', 'title', 'deleted_at'];
        $categories = Category::withTrashed()
                           ->orderBy('position')
                           ->get($columns);
        foreach ($categories as &$category) {
            $category['forums'] = Forum::withTrashed()
                                    ->where('category_id', $category->id)
                                    ->whereNull('parent_id')
                                    ->orderBy('position')
                                    ->get($columns);
            foreach ($category['forums'] as &$forum) {
                $forum['children'] = Forum::withTrashed()
                                        ->where('parent_id', $forum->id)
                                        ->orderBy('position')
                                        ->get($columns);
            }
        }
        return view('admin.sections.positions', ['categories' => $categories]);
    }

}
