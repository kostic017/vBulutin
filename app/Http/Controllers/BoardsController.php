<?php

namespace App\Http\Controllers;

use Auth;
use Validator;

use App\Board;
use App\Directory;

class BoardsController extends Controller {

    public function show($address) {
        $board = get_board($address);

        if (!$board->is_visible) {
            return alert_redirect(route('website.index'), 'info', 'Forum trenutno nije vidljiv.');
        }

        return view('public.index')->with('categories', $board->categories()->orderBy('position')->get());
    }

    public function create($directory_slug) {
        return view('admin.boards.create')
            ->with('directories', Directory::all())
            ->with('force_directory', Directory::where('slug', $directory_slug)->firstOrFail());
    }

    public function edit($address) {
        return view('admin.index')
            ->with('directories', Directory::all())
            ->with('board', get_board($address));
    }

    public function store() {
        $request = request();

        $validator = Validator::make($request->all(), [
            'address' => 'required|max:255|alpha_dash|not_in:www|unique:boards',
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $board = new Board;
        $board->address = $request->address;
        $board->owner_id = Auth::id();
        $board->title = $request->title;
        $board->is_visible = $request->is_visible;
        $board->directory_id = $request->directory_id;
        $board->description = $request->description;
        $board->save();

        return alert_redirect(route('admin.index', [$board->address]), 'success', 'Forum uspešno napravljen.');
    }

    public function update($address) {
        $request = request();
        $board = get_board($address);

        $validator = Validator::make($request->all(), [
            'address' => "required|max:255|alpha_dash|not_in:www|unique:boards,address,{$board->id}",
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $board->address = $request->address;
        $board->title = $request->title;
        $board->is_visible = $request->is_visible;
        $board->directory_id = $request->directory_id;
        $board->description = $request->description;
        $board->save();

        return alert_redirect(route('admin.index', [$board->address]), 'success', 'Forum uspešno izmenjen.');
    }

}
