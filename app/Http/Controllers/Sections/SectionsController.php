<?php

namespace App\Http\Controllers\Sections;

use Session;
use Validator;
use Illuminate\Http\Request;

abstract class SectionsController extends Controller
{
    protected $model = null;
    protected $table = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter = request()->query('filter', 'active');
        $perPage = (int)request()->query('perPage', 10);

        if ($perPage % 10) {
            $perPage = 10; // moze da ima samo neku od ponudjenih vrednosti
        }

        if ($filter === 'all') {
            $rows = $perPage ? $this->model::withTrashed()->paginate($perPage) : $this->model::withTrashed()->get();
        } elseif ($filter === 'active') {
            $rows = $perPage ? $this->model::paginate($perPage) : $this->model::get();
        } elseif ($filter === 'deleted') {
            $rows = $perPage ? $this->model::onlyTrashed()->paginate($perPage) : $this->model::onlyTrashed()->get();
        }

        return view('admin.sections')
            ->with('table', $this->table)
            ->with('rows', $rows)
            ->with('sortColumn', 'id')
            ->with('sortOrder', 'asc')
            ->with('perPage', $perPage);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($section = $this->model::find($id)) {
            return view("admin.{$this->table}.edit")->with('section', $section);
        }
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

        if ($section = $this->model::find($id)) {
            $section->title = $request->title;
            $section->description = e($request->description);
            $section->save();

            Session::flush("Section successfully updated.");
        }

        return redirect(route("admin.{$this->table}.show", ['section' => $id]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($section = $this->model::find($id)) {
            $section->delete();
            return redirect(route("admin.{$this->table}.index"));
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
        return view('admin.positions', ['categories' => $categories]);
    }
}
