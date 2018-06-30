<?php

namespace App\Http\Controllers;

use Validator;
use App\Board;
use Illuminate\Validation\Rule;

abstract class SectionsController extends Controller {
    protected $model = null;
    protected $table = null;

    public function edit($board_address, $slug) {
        $section = $this->model::where('slug', $slug)->firstOrFail();
        return view("admin.sections.{$this->table}.edit")->with($this->singular, $section);
    }

    public function update($board_address, $id) {
        $request = request();

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

        return alert_redirect(route("{$this->table}.show.admin", [$section->slug]), 'success', __('db.updated'));
    }

    public function destroy($board_address, $id) {
        $section = $this->model::findOrFail($id);
        $section->delete();
        return alert_redirect(route("{$this->table}.index"), 'success', __('db.deleted'));
    }

    public function restore($board_address, $id) {
        $section = $this->model::onlyTrashed()->findOrFail($id);
        $section->restore();
        return alert_redirect(route("{$this->table}.index"), 'success', __('db.restored'));
    }

    abstract public function create($board_address);
    abstract public function store($board_address);
    abstract public function show($board_address, $section_slug);
    abstract public function show_admin($board_address, $section_slug);
}
