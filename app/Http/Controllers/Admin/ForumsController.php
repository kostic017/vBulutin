<?php

namespace App\Http\Controllers\Admin;

use Session;
use Validator;
use App\Forum;
use App\Category;
use Illuminate\Http\Request;
use App\Exceptions\IdNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ForumsController extends SectionsController
{
    protected $table = 'forums';
    protected $singular = 'forum';
    protected $model = 'App\Forum';

    public function index() {
        return parent::index();
    }

    public function edit($id) {
        return parent::edit($id);
    }

    public function update(Request $request, $id) {
        return parent::update($request, $id);
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
            $forum = Forum::findOrFail($id);

            $children = Forum::where('parent_id', $id)->get();
            foreach ($children as $child) {
                $child->delete();
            }

            $forum->delete();
            return back()->with([
                'alert-type' => 'success',
                'message' => __('db.deleted')
            ]);
        } catch (ModelNotFoundException $e) {
            throw new IdNotFoundException($id, $this->table);
        }
    }

    public function restore($id) {
        try {
            $forum = Forum::onlyTrashed()->findOrFail($id);

            if ($forum->parent_id) {
                $parent = Forum::withTrashed()->findOrFail($forum->parent_id);
                if ($parent->trashed()) {
                    return back()->with([
                        'alert-type' => 'error',
                        'message' => __('admin.parent-deleted')
                    ]);
                }
            }

            $category = Category::withTrashed()->findOrFail($forum->category_id);
            if ($category->trashed()) {
                return back()->with([
                    'alert-type' => 'error',
                    'message' => __('admin.category-deleted')
                ]);
            }

            $forum->restore();
            return back()->with([
                'alert-type' => 'success',
                'message' => __('db.restored')
            ]);

        } catch (ModelNotFoundException $e) {
            throw new IdNotFoundException($id, $this->table);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all(['id', 'title']);
        $rootForums = Forum::whereNull('parent_id')->get(['id', 'title']);
        return view('admin.sections.forums.create')
                ->with('categories', $categories)
                ->with('rootForums', $rootForums);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
        $forum->description = e($request->description);
        $forum->category_id = $request->category_id;
        $forum->parent_id = $request->parent_id ?? null;

        $forum->position = (
            $forum->parent_id ?
                Forum::where('parent_id', $forum->parent_id)->max('position') :
                Forum::where('category_id', $forum->category_id)->where('parent_id', null)->max('position')
        ) + 1;

        $forum->save();

        return redirect(route('forums.index'))->with([
            'alert-type' => 'success',
            'message' => 'db.stored'
        ]);
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
            $forum = Forum::withTrashed()->where('id', $id)->firstOrFail();
            $category = Category::withTrashed()->where('id', $forum->category_id)->get(['id', 'slug', 'title'])->first();
            $parentForum = Forum::withTrashed()->where('id', $forum->parent_id)->get(['id', 'slug', 'title'])->first();
            return view('admin.sections.forums.show')
                ->with('forum', $forum)
                ->with('category', $category)
                ->with('parentForum', $parentForum);
        } catch (ModelNotFoundException $e) {
            throw new IdNotFoundException($id, $this->table);
        }
    }

}
