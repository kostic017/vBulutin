<?php

namespace App\Http\Controllers\Admin;

use Session;
use Validator;
use App\Forum;
use App\Category;
use Illuminate\Http\Request;
use App\Exceptions\IdNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriesController extends SectionsController
{
    protected $table = 'categories';
    protected $singular = 'category';
    protected $model = 'App\Category';

    public function index() {
        return parent::index();
    }

    public function edit($id) {
        return parent::edit($id);
    }

    public function update(Request $request, $id) {
        return parent::update($request, $id);
    }

    public function restore($id) {
        return parent::restore($id);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sections.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:categories'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category = new Category;
        $category->title = $request->title;
        $category->description = e($request->description);
        $category->position = Category::max('position') + 1;
        $category->save();

        return redirect(route('categories.index'))->with([
            'alert-type' => 'success',
            'message' => __('db.stored')
        ]);;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $category = Category::withTrashed()->where('id', $id)->firstOrFail();
            return view('admin.sections.categories.show')->with('category', $category);
        } catch (ModelNotFoundException $e) {
            throw new IdNotFoundException($id, $this->table);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $category = $this->model::findOrFail($id);

            $forums = Forum::where('category_id', $id)->get();
            foreach ($forums as $forum) {
                $forum->delete();
            }

            $category->delete();
            return redirect(route("{$this->table}.index"))->with([
                'alert-type' => 'success',
                'message' => __('db.deleted')
            ]);
        } catch (ModelNotFoundException $e) {
            throw new IdNotFoundException($id, $this->table);
        }
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
