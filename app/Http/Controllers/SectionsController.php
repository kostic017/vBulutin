<?php

namespace App\Http\Controllers;

use Session;
use Validator;
use App\Forum;
use App\Section;
use Illuminate\Http\Request;

class SectionsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPage = (int)request()->query('perPage', 10);
        $sections = Section::select(['id', 'title', 'position']);
        if ($perPage > 0) {
            $sections = $sections->paginate($perPage);
        } else {
            $sections = $sections->get();
        }
        return view('admin.table')
            ->with('table', 'sections')
            ->with('rows', $sections)
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
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:sections'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $section = new Section;
        $section->title = $request->title;
        $section->description = e($request->description);
        $section->position = Section::max('position') + 1;
        $section->save();

        Session::flush("Section successfully created.");
        return redirect(route('sections.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if ($section = Section::where('id', $id)->first()) {
            return view('admin.sections.show')->with('section', $section);
        }
        Session::flush('info', "Data you're searching for doesn't exist");
        return redirect(route('sections.index'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($section = Section::find($id)) {
            return view('admin.sections.edit')->with('section', $section);
        }
        Session::flush('info', "Data you're searching for doesn't exist");
        return redirect(route('sections.index'));
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
        $validator = Validator::make($request->all(), [
            'title' => "required|max:255|unique:forums,title,{$id}",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($section = Section::find($id)) {
            $section->title = $request->title;
            $section->description = e($request->description);
            $section->save();

            Session::flush("Section successfully updated.");
        } else {
            Session::flush('info', "Data you're searching for doesn't exist");
        }

        return redirect(route('sections.show', ['section' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($section = Section::find($id)) {
            $section->delete();
            return redirect(route('sections.index'));
        }
        Session::flush('info', "Data you're searching for doesn't exist");
        return redirect(route('sections.index'));
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
