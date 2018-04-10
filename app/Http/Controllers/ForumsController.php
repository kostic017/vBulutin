<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use App\Forum;
use App\Section;
use Illuminate\Http\Request;

class ForumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = (int)request()->query('perPage', 10);
        $forums = Forum::select(['id', 'title', 'position']);
        if ($perPage > 0) {
            $forums = $forums->paginate($perPage);
        } else {
            $forums = $forums->get();
        }
        return view('admin.table')
            ->with('table', 'forums')
            ->with('rows', $forums)
            ->with('sortColumn', 'id')
            ->with('sortOrder', 'asc')
            ->with('perPage', $perPage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $sections = Section::all(['id', 'title']);
        $rootForums = Forum::whereNull('parent_id')->get(['id', 'title']);
        return view('admin.forums.create')
                ->with('sections', $sections)
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
            $request->section_id = Forum::where('id', $request->parent_id)->pluck('section_id')->first();
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:forums',
            'section_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $forum = new Forum;
        $forum->title = $request->title;
        $forum->description = e($request->description);
        $forum->section_id = $request->section_id;
        $forum->parent_id = $request->parent_id ?? null;

        if ($forum->parent_id) {
            $where = ['parent_id' => $forum->parent_id];
        } else {
            $where = [
                ['section_id' => $forum->section_id],
                ['parent_id' => null]
            ];
        }

        $forum->position = Forum::where($where)->max('position') + 1;
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
            return view('admin.forums.show')->with('forum', $forum);
        }
        Session::flush('info', "Data you're searching for doesn't exist");
        return view('admin.forum.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
