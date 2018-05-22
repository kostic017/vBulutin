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

class ForumsController extends SectionsController
{
    protected $table = 'forums';
    protected $singular = 'forum';
    protected $model = 'App\Forum';

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
        $categories = Category::all(['id', 'title']);
        $rootForums = Forum::select (
            'forums.id AS id',
            'forums.title AS title',
            'categories.id AS category_id',
            'categories.deleted_at'
        )->join('categories', 'forums.category_id', 'categories.id')
         ->whereNull('parent_id')
         ->whereNull('categories.deleted_at')
         ->get();
        return view('admin.sections.forums.create')
                ->with('categories', $categories)
                ->with('rootForums', $rootForums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        if (isset($request->parent_id)) {
            $request->request->add(['category_id' => Forum::find($request->parent_id)->pluck('category_id')->first()]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:forums',
            'category_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $forum = new Forum;
        $forum->title = $request->title;
        $forum->slug = str_slug($forum->title);
        $forum->description = e($request->description);
        $forum->category_id = $request->category_id;
        $forum->parent_id = $request->parent_id ?? null;

        $forum->position = (
            $forum->parent_id ?
                Forum::where('parent_id', $forum->parent_id)->max('position') :
                Forum::where('category_id', $forum->category_id)->where('parent_id', null)->max('position')
        ) + 1;

        $forum->save();

        $forum->slug = unique_slug($forum->title, $forum->id);
        $forum->save();

        return redirect(route('forums.show', ['forum' => $forum->slug]))->with([
            'alert-type' => 'success',
            'message' => 'db.stored'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug): View
    {
        try {
            $forum = Forum::withTrashed()->where('slug', $slug)->firstOrFail();
            $category = Category::withTrashed()->where('id', $forum->category_id)->get(['id', 'slug', 'title'])->first();
            $parentForum = Forum::withTrashed()->where('id', $forum->parent_id)->get(['id', 'slug', 'title'])->first();
            return view('admin.sections.forums.show')
                ->with('forum', $forum)
                ->with('category', $category)
                ->with('parentForum', $parentForum);
        } catch (ModelNotFoundException $e) {
            throw new SlugNotFoundException($slug, "forums");
        }
    }

}
