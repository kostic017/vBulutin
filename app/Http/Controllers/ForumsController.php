<?php

namespace App\Http\Controllers;

use Validator;

use App\Forum;
use App\Board;
use App\Category;

class ForumsController extends SectionsController {
    protected $table = 'forums';
    protected $model = 'App\Forum';

    public function index($board_address) {
        $columns = ['id', 'title', 'deleted_at'];
        $categories = Category::withTrashed()
            ->orderBy('position')
            ->get($columns);
        foreach ($categories as &$category) {
            $category['forums'] = Forum::withTrashed()
                ->where('category_id', $category->id)
                ->whereNull('parent_id')
                ->orderBy('position')
                ->get($columns);
            foreach ($category['forums'] as &$forum) {
                $forum['children'] = Forum::withTrashed()
                    ->where('parent_id', $forum->id)
                    ->orderBy('position')
                    ->get($columns);
            }
        }
        return view('admin.sections.index', ['categories' => $categories]);
    }

    public function edit($board_address, $slug) {
        return parent::edit($board_address, $slug);
    }

    public function update($board_address, $id) {
        return parent::update($board_address, $id);
    }

    public function destroy($board_address, $id) {
        return parent::destroy($board_address, $id);
    }

    public function restore($board_address, $id) {
        return parent::restore($board_address, $id);
    }

    public function create($board_address) {
        $categories = Category::all(['id', 'title']);
        $rootForums = Forum::select(
            'forums.id AS id',
            'forums.title AS title',
            'categories.id AS category_id',
            'categories.deleted_at'
        )->join('categories', 'forums.category_id', 'categories.id')
         ->whereNull('parent_id')
         ->whereNull('categories.deleted_at')
         ->get();
        return view('admin.sections.forums.create')
                ->with('categories', $categories)
                ->with('rootForums', $rootForums);
    }

    public function store($board_address) {
        $request = request();

        if (isset($request->parent_id)) {
            $request->request->add(['category_id' => Forum::find($request->parent_id)->pluck('category_id')->first()]);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255|unique:forums',
            'category_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $forum = new Forum;
        $forum->title = $request->title;
        $forum->slug = str_slug($forum->title);
        $forum->description = $request->description;
        $forum->category_id = $request->category_id;
        $forum->parent_id = $request->parent_id ?? null;

        $forum->position = (
            $forum->parent_id ?
                Forum::where('parent_id', $forum->parent_id)->max('position') :
                Forum::where('category_id', $forum->category_id)->where('parent_id', null)->max('position')
        ) + 1;

        $forum->save();

        $forum->slug = unique_slug($forum->title, $forum->id);
        $forum->save();

        return alert_redirect(route('forums.show.admin', [$forum->slug]), 'success', 'db.stored');
    }

    public function show_admin($board_address, $forum_slug) {
        $board = Board::where('address', $board_address)->firstOrFail();
        $forum = $board->forums()->withTrashed()->where('forums.slug', $forum_slug)->firstOrFail();
        return view('admin.sections.forums.show')->with('forum', $forum);
    }

    public function show($board_address, $forum_slug) {
        $board = Board::where('address', $board_address)->firstOrFail();
        $forum = $board->forums()->where('forums.slug', $forum_slug)->firstOrFail();

        return view('public.forum')
            ->with('forum', $forum)
            ->with('current_board', $board)
            ->with('is_admin', $board->is_admin())
            ->with('child_forums', $forum->children()->get())
            ->with('topics', $forum->topics ()->orderBy('updated_at', 'desc')->get());
    }

    public function lock($board_address, $id) {
        $forum = Forum::findOrFail($id);
        $forum->is_locked = !$forum->is_locked;
        $forum->save();
        return redirect()->back();
    }

}
