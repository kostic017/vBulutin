<?php

namespace App\Http\Controllers;

use Session;
use App\Forum;
use App\Section;

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
        //
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
            return view('admin.forums.show')->with('section', $forum);
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
