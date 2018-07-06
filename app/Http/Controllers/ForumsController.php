<?php

namespace App\Http\Controllers;

use Validator;

use App\Forum;
use App\Board;
use App\Category;

class ForumsController extends SectionsController {
    protected $table = 'forums';
    protected $singular = 'forum';
    protected $model = 'App\Forum';

    public function index($board_address) {
        $board = get_board($board_address);
        $categories = $board->categories()
                            ->withTrashed()
                            ->orderBy('position')
                            ->get();
        foreach ($categories as &$category) {
            $category['parent_forums'] =
                $category->forums()
                         ->withTrashed()
                         ->whereNull('parent_id')
                         ->orderBy('position')
                         ->get();
            foreach ($category['parent_forums'] as &$forum) {
                $forum['child_forums'] =
                    $forum->children()
                          ->withTrashed()
                          ->orderBy('position')
                          ->get();
            }
        }
        return view('admin.sections.index', ['categories' => $categories]);
    }

    public function edit($board_address, $slug) {
        return view('admin.sections.forums.edit')->with(
            'forum', get_board($board_address)->forums()->where('forums.slug', $slug)->firstOrFail()
        );
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

    public function create($board_address, $force_section, $force_id) {
        $board = get_board($board_address);
        if ($force_section === 'category') {
            $category = $board->categories()->findOrFail($force_id);
            $parent_forum = null;
        } else {
            $parent_forum = $board->forums()->findOrFail($force_id);
            $category = $parent_forum->category;
        }
        return view('admin.sections.forums.create')->with(compact('category', 'parent_forum'));
    }

    public function store($board_address) {
        $request = request();
        $category = Category::findOrFail($request->category_id);

        $validator = Validator::make($request->all(), [
            'title' => [
                'required',
                'max:255',
                function ($attribute, $value, $fail) use ($category) {
                    if ($category->forums()->where('forums.title', $value)->count()) {
                        return $fail('U jednoj kategoriji ne mogu postojati dva foruma koja se isto zovu.');
                    }
                },
            ],
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
        $forum->parent_id = $request->parent_id;

        $forum->position = (
            $forum->parent_id ?
                $category->board->forums()->where('parent_id', $forum->parent_id) :
                $category->board->forums()->whereNull('parent_id')->where('category_id', $forum->category_id)
        )->max('forums.position') + 1;

        $collisions = $category->board->forums()->where('forums.slug', $forum->slug)->count();
        if ($collisions > 0) {
            $forum->slug .= '-' . $collisions;
        }

        $forum->save();

        return alert_redirect(route('forums.show.admin', [$board_address, $forum->slug]), 'success', __('db.stored'));
    }

    public function show_admin($board_address, $forum_slug) {
        $board = get_board($board_address);
        $forum = $board->forums()->withTrashed()->where('forums.slug', $forum_slug)->firstOrFail();
        return view('admin.sections.forums.show')->with('forum', $forum);
    }

    public function show($board_address, $forum_slug) {
        $board = get_board($board_address);
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
