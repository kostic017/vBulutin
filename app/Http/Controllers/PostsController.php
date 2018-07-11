<?php

namespace App\Http\Controllers;

use Auth;
use Carbon;
use Validator;

use App\Post;
use App\Topic;
use App\ReadTopic;

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
        $post = $topic->last_post();

        if (Auth::id() === $post->user->id && !$post->trashed()) {
            $date = Carbon::now()->toDateTimeString();
            $post->content .= "\n\n[b]========== Ažuriranje " . extract_date($date) . " "  . extract_time($date) . " ==========[/b]\n\n" . $request->content;
            $post->save();
        } else {
            $post = new Post;
            $post->content = $request->content;
            $post->topic_id = $topic->id;
            $post->user_id = Auth::id();
            $post->save();
        }

        $topic->touch();
        ReadTopic::where('topic_id', $topic->id)->delete();

        return redirect(route_topic_show($topic) . "#post-{$post->id}");
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

        $topic->updated_at = $topic->posts()->orderBy('created_at', 'desc')->firstOrFail()->created_at;
        $topic->save();

        return redirect()->back();
    }

    public function restore($id) {
        $post = Post::onlyTrashed()->findOrFail($id);
        if ($post->topic->trashed())
            $post->topic->restore();
        $post->restore();
        $post->topic->touch();
        return redirect(route_topic_show($post->topic) . "#post-{$post->id}");
    }

}
