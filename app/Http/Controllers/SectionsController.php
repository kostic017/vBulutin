<?php

namespace App\Http\Controllers;

use App\Forum;
use App\Section;

class SectionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.table')
            ->with('table', 'sections')
            ->with('rows', Section::all(["id", "title", "position"]))
            ->with('sortColumn', 'id')
            ->with('sortOrder', 'asc');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.sections.create');
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
        //
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

    public function positions()
    {
        $columns = ['id', 'title'];
        $sections = Section::orderBy('position')
                           ->get($columns)
                           ->toArray();
        foreach ($sections as &$section) {
            $section['forums'] = Forum::where('section_id', $section['id'])
                                      ->whereNull('parent_id')
                                      ->orderBy('position')
                                      ->get($columns)
                                      ->toArray();
            foreach ($section['forums'] as &$forum) {
                $forum['children'] = Forum::where('parent_id', $forum['id'])
                                          ->orderBy('position')
                                          ->get($columns)
                                          ->toArray();
            }
        }
        return view('admin.positions', ['sections' => $sections]);
    }
}
