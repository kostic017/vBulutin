<?php

namespace App\Http\Controllers\Sections;

use Session;
use Validator;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Helpers\Common\Functions;
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
        $cols = ['id', 'title', 'category'];
        $max = (int)config('custom.pagination.max');
        $step = (int)config('custom.pagination.step');

        $validator = Validator::make($request->all(), [
            'perPage' => "integer|between:0,{$max}",
            'filter' => Rule::in(['all', 'active', 'trashed']),
            'sort_column' => Rule::in($cols),
            'sort_order' => Rule::in(['asc', 'desc']),
            'search_column' => Rule::in($cols),
        ]);

        $errors = $validator->errors();

        $searchQuery = mb_strtolower(request('search_query', ''));
        $perPage =  $request->has('perPage') && !$errors->has('perPage') ? (int)$request->perPage : $step;
        $filter = $request->has('filter') && !$errors->has('filter') ? $request->filter : 'active';
        $sortColumn = $request->has('sort_column') && !$errors->has('sort_column') ? $request->sort_column : 'id';
        $sortOrder = $request->has('sort_order') && !$errors->has('sort_order') ? $request->sort_order : 'asc';
        $searchColumn = $request->has('search_column') && !$errors->has('search_column') ? $request->search_column : 'title';

        if ($remainder = $perPage % $step) {
            $perPage = ($perPage - $remainder) ?: $step;
            $errors->add('perPage', '');
        }

        if ($errors->any()) {
            $queries = [
                'perPage' => $perPage,
                'filter' => $filter,
                'sort_column' => $sortColumn,
                'sort_order' => $sortOrder,
                'search_column' => $searchColumn,
            ];

            if (isNotEmpty($searchQuery)) {
                $queries['search_query'] = $searchQuery;
            }

            return redirect(request()->fullUrlWithQuery($queries));
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
            $query = $query->select(
                        'forums.id AS id',
                        'forums.title AS title',
                        'forums.deleted_at AS deleted_at',
                        'categories.title AS category'
                    )->join('categories', 'forums.category_id', 'categories.id');
        }

        if (isNotEmpty($searchQuery)) {
            $query->where("{$this->table}.{$searchColumn}", 'LIKE', "%{$searchQuery}%");
        }

        $query = $query->orderBy("{$this->table}.{$sortColumn}", $sortOrder);

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
            ->with(compact('rows', 'perPage', 'filter', 'sortColumn', 'sortOrder', 'searchColumn', 'searchQuery'));
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
