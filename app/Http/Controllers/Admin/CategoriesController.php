<?php

namespace App\Http\Controllers\Admin;

use Session;
use Validator;

use App\Forum;
use App\Category;
use App\Exceptions\SlugNotFoundException;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoriesController extends SectionsController
{
    protected $table = 'categories';
    protected $singular = 'category';
    protected $model = 'App\Category';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        return parent::index();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function edit(string $slug): View
    {
        return parent::edit($slug);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $slug): RedirectResponse
    {
        return parent::update($request, $slug);
    }

    /**
     * Restore a soft-deleted model instance.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(string $slug): RedirectResponse
    {
        return parent::restore($slug);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.sections.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
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

        return redirect(route('categories.show', ['category' => $category->slug]))->with([
            'alert-type' => 'success',
            'message' => __('db.stored')
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug): view
    {
        try {
            $category = Category::withTrashed()->where('slug', $slug)->firstOrFail();
            return view('admin.sections.categories.show')->with('category', $category);
        } catch (ModelNotFoundException $e) {
            throw new SlugNotFoundException($slug, "categories");
        }
    }

    /**
     * Generates ordered tree of all forums and categories.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function positions(): View
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
