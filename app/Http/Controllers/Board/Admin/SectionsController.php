<?php

namespace App\Http\Controllers\Board\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Validation\Rule;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class SectionsController extends Controller
{
    protected $model = null;
    protected $table = null;
    protected $singular = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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

        $validator = \Validator::make($request->all(), [
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

            if (is_not_empty($searchQuery)) {
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
            $query->withTrashed();
        } elseif ($filter === 'trashed') {
            $query->onlyTrashed();
        }

        if ($this->table === 'forums') {
            $query->select(
                'forums.id AS id',
                'forums.slug AS slug',
                'forums.title AS title',
                'forums.deleted_at AS deleted_at',
                'categories.title AS category'
            )->join('categories', 'forums.category_id', 'categories.id');
        }

        if (is_not_empty($searchQuery)) {
            $query->where(
                $searchColumn === 'category' ? 'categories.title' : "{$this->table}.{$searchColumn}",
                'LIKE', "%{$searchQuery}%"
            );
        }

        $query->orderBy(
            $sortColumn === 'category' ? 'categories.title' : "{$this->table}.{$sortColumn}",
            $sortOrder
        );

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
            ->with(compact('rows', 'perPage', 'max', 'step', 'filter', 'sortColumn', 'sortOrder', 'searchColumn', 'searchQuery'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function edit($slug)
    {
        $section = $this->model::where('slug', $slug)->firstOrFail();
        return view("admin.sections.{$this->table}.edit")->with($this->singular, $section);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($request, $id)
    {
        $section = $this->model::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title' => "required|max:255|unique:{$this->table},title,{$section->id}",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $section->title = $request->title;
        $section->slug = unique_slug($section->title, $section->id);
        $section->description = $request->description;
        $section->save();

        return alert_redirect(route("admin.{$this->singular}.show", [$this->singular => $section->slug]), 'success', __('db.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $section = $this->model::findOrFail($id);
        $section->delete();
        return alert_redirect(route("admin.{$this->singular}.index"), 'success', __('db.deleted'));
    }

    /**
     * Restore a soft-deleted model instance.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $section = $this->model::onlyTrashed()->findOrFail($id);
        $section->restore();
        return alert_redirect(route("admin.{$this->singular}.index"), 'success', __('db.restored'));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    abstract public function show($slug);

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    abstract public function create();

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    abstract public function store($request);

}
