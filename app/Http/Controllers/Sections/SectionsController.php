<?php

namespace App\Http\Controllers\Sections;

use Session;
use Validator;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\DataNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class SectionsController extends Controller
{
    protected $model = null;
    protected $table = null;
    protected $singular = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filter = request()->query('filter', 'active');
        $perPage = (int)request()->query('perPage', 10);
        $step = (int)config('custom.pagination.step');

        if ($perPage % $step) {
            $perPage = $step;
        }

        if ($filter === 'all') {
            $rows = $perPage ? $this->model::withTrashed()->paginate($perPage) : $this->model::withTrashed()->get();
        } elseif ($filter === 'active') {
            $rows = $perPage ? $this->model::paginate($perPage) : $this->model::get();
        } elseif ($filter === 'deleted') {
            $rows = $perPage ? $this->model::onlyTrashed()->paginate($perPage) : $this->model::onlyTrashed()->get();
        }

        if ($this->table === 'forums') {
            foreach ($rows as $row) {
                $row->category_name = Category::withTrashed()->where('id', $row->category_id)->pluck('title')->first();
            }
        }

        return view('admin.sections.table')
            ->with('table', $this->table)
            ->with('rows', $rows)
            ->with('sortColumn', 'id')
            ->with('sortOrder', 'asc')
            ->with('filter', $filter)
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
        try {
            $section = $this->model::findOrFail($id);
            return view("admin.sections.{$this->table}.edit")->with($this->singular, $section);
        } catch (ModelNotFoundException $e) {
            throw new DataNotFoundException($this->table, $id);
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

        try {
            $section = $this->model::findOrFail($id);
            $section->title = $request->title;
            $section->description = e($request->description);
            $section->save();

            return redirect(route("{$this->table}.show", [$this->singular => $id]))->with([
                'alert-type' => 'success',
                'message' => __('db.updated')
            ]);
        } catch (ModelNotFoundException $e) {
            throw new DataNotFoundException($this->table, $id);
        }
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
            $section = $this->model::findOrFail($id);
            $section->delete();
            return redirect(route("{$this->table}.index"))->with([
                'alert-type' => 'success',
                'message' => __('db.deleted')
            ]);
        } catch (ModelNotFoundException $e) {
            throw new DataNotFoundException($this->table, $id);
        }
    }

    public function restore($id) {
        try {
            $section = $this->model::onlyTrashed()->findOrFail($id);
            $section->restore();
            return redirect(route("{$this->table}.index"))->with([
                'alert-type' => 'success',
                'message' => __('db.resored')
            ]);
        } catch (ModelNotFoundException $e) {
            throw new DataNotFoundException($this->table, $id);
        }
    }

}
