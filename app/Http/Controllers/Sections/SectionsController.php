<?php

namespace App\Http\Controllers\Sections;

use Session;
use Validator;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        /*
        |--------------------------------------------------------------------------
        | Get Valid Input
        |--------------------------------------------------------------------------
        */

        $step = (int)config('custom.pagination.step');

        $perPage = request('perPage', $step);
        $filter = request('filter', 'active');
        $sortColumn = request('sort_column', 'id');
        $sortOrder = request('sort_order', 'asc');

        $validate = compact('perPage', 'filter', 'sortColumn', 'sortOrder');
        $validator = Validator::make($validate, [
            'perPage' => 'numeric',
            'filter' => Rule::in(['all', 'active', 'trashed']),
            'sortColumn' => Rule::in(['id', 'title', 'category']),
            'sortOrder' => Rule::in(['asc', 'desc']),
        ]);

        $errors = $validator->errors();

        $perPage = (int)($errors->has('perPage') ? $step : $perPage);
        $filter = $errors->has('filter') ? 'active' : $filter;
        $sortColumn = $errors->has('sortColumn') ? 'id' : $sortColumn;
        $sortOrder = $errors->has('sortOrder') ? 'asc' : $sortOrder;

        /*
        |--------------------------------------------------------------------------
        | Build Query And Fetch Data
        |--------------------------------------------------------------------------
        */

        $query = $this->model::query();

        if ($filter === 'all') {
            $query = $query->withTrashed();
        } elseif ($filter === 'trashed') {
            $query = $query->onlyTrashed();
        }

        if ($this->table === 'forums') {
            $query = $query->join('categories', 'forums.category_id', 'categories.id')
                        ->select('forums.id as id', 'forums.title as title', 'categories.title as category_title');
        }

        if ($sortColumn === 'category') {
            $query->orderBy('category_title', $sortOrder);
        } else {
            $query = $query->orderBy("{$this->table}.{$sortColumn}", $sortOrder);
        }

        if ($perPage) {
            $rows = $query->paginate($perPage);
        } else {
            $rows = $query->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Return Response
        |--------------------------------------------------------------------------
        */

        return view('admin.sections.table')
            ->with('table', $this->table)
            ->with('rows', $rows)
            ->with('perPage', $perPage)
            ->with('filter', $filter)
            ->with('sortColumn', $sortColumn)
            ->with('sortOrder', $sortOrder);
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
                'message' => __('db.restored')
            ]);
        } catch (ModelNotFoundException $e) {
            throw new DataNotFoundException($this->table, $id);
        }
    }

}