<?php

namespace App\Http\Controllers\Sections;

use Session;
use Validator;
use App\Forum;
use App\Category;
use Illuminate\Http\Request;

class ForumsController extends SectionsController
{
    protected $table = 'forums';
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

    public function destroy($id) {
        return parent::destroy($id);
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
        return view('admin.forums.create')
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
            $request->request->add(['category_id' => Forum::where('id', $request->parent_id)->pluck('category_id')->first()]);
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

        Session::flush("Forum successfully created.");
        return redirect(route('forums.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($forum = Forum::where('id', $id)->first()) {
            $category = Category::where('id', $forum->category_id)->get(['id', 'slug', 'title'])->first();
            $parentForum = Forum::where('id', $forum->parent_id)->get(['id', 'slug', 'title'])->first();
            return view('admin.forums.show')->with('forum', $forum)
                                            ->with('category', $category)
                                            ->with('parentForum', $parentForum);
        }
    }

}
