<?php

namespace App\Http\Controllers;

use Auth;
use Carbon;
use Validator;

use App\Post;
use App\Topic;

class PostsController extends Controller {

    public function store() {
        $request = request();

        $validator = Validator::make($request->all(), [
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous() . '#scform')->withErrors($validator)->withInput();
        }

        $topic = Topic::findOrFail($request->topic_id);
        $last_post = $topic->last_post();

        if (Auth::id() === $last_post->user->id && !$last_post->trashed()) {
            $last_post->content .= "\n\n[b]========== " . __('generic.update') . ' ' .
                Carbon::now()->toDateTimeString() . " ==========[/b]\n\n" . $request->content;
            $last_post->save();
        } else {
            $post = new Post;
            $post->content = $request->content;
            $post->topic_id = $topic->id;
            $post->user_id = Auth::id();
            $post->save();
        }

        $topic->touch();

        return redirect(route_topic_show($topic));
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);
        $topic = $post->topic;

        if ($topic->solution_id === (int)$id) {
            $topic->solution_id = null;
            $topic->save();
        }

        $post->delete();

        if ($topic->posts()->count() == 0) {
            $topic->delete();
            return redirect(route_forum_show($topic->forum));
        }

        return redirect()->back();
    }

    public function restore($id) {
        $post = Post::onlyTrashed()->findOrFail($id);
        if ($post->topic->trashed())
            $post->topic->restore();
        $post->restore();
        return redirect()->back();
    }

}
