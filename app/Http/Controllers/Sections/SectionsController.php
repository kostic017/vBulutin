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
        | Get Valid or Correct Bad Input
        |--------------------------------------------------------------------------
        */

        $request = request();
        $max = (int)config('custom.pagination.max');
        $step = (int)config('custom.pagination.step');

        $validator = Validator::make($request->all(), [
            'perPage' => "integer|between:0,{$max}",
            'filter' => Rule::in(['all', 'active', 'trashed']),
            'sort_column' => Rule::in(['id', 'title', 'category']),
            'sort_order' => Rule::in(['asc', 'desc']),
        ]);

        $errors = $validator->errors();

        $perPage =  $request->has('perPage') && !$errors->has('perPage') ? (int)$request->perPage : $step;
        $filter = $request->has('filter') && !$errors->has('filter') ? $request->filter : 'active';
        $sortColumn = $request->has('sort_column') && !$errors->has('sort_column') ? $request->sort_column : 'id';
        $sortOrder = $request->has('sort_order') && !$errors->has('sort_order') ? $request->sort_order : 'asc';

        if ($remainder = $perPage % $step) {
            $perPage = ($perPage - $remainder) ?: $step;
            $errors->add('perPage', '');
        }

        if ($errors->any()) {
            return redirect(request()->fullUrlWithQuery([
                'perPage' => $perPage,
                'filter' => $filter,
                'sort_column' => $sortColumn,
                'sort_order' => $sortOrder
            ]));
        }

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
