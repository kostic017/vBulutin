<?php

namespace App\Http\Controllers\Sections;

use Session;
use Validator;
use App\Forum;
use App\Category;
use Illuminate\Http\Request;

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

    public function destroy($id) {
        return parent::destroy($id);
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

        Session::flush("Category successfully created.");
        return redirect(route('categories.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($category = Category::withTrashed()->where('id', $id)->first()) {
            return view('admin.sections.categories.show')->with('category', $category);
        }
    }

    public function positions()
    {
        $columns = ['id', 'title'];
        $categories = Category::orderBy('position')
                           ->get($columns)
                           ->toArray();
        foreach ($categories as &$category) {
            $category['forums'] = Forum::where('category_id', $category['id'])
                                      ->whereNull('parent_id')
                                      ->orderBy('position')
                                      ->get($columns)
                                      ->toArray();
            foreach ($category['forums'] as &$forum) {
                $forum['children'] = Forum::where('parent_id', $forum['id'])
                                          ->orderBy('position')
                                          ->get($columns)
                                          ->toArray();
            }
        }
        return view('admin.sections.positions', ['categories' => $categories]);
    }

}
