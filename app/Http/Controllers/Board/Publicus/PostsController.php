<?php

namespace App\Http\Controllers\Board\Publicus;

use App\Post;
use App\Topic;

class PostsController
{
    public function store($request)
    {
        $request = request();

        $validator = \Validator::make($request->all(), [
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous() . '#scform')->withErrors($validator)->withInput();
        }

        $topic = Topic::findOrFail($request->topic_id);
        $post = $topic->last_post();

        if (\Auth::id() === $post->user->id) {
            $post->content .= "\n\n[b]========== " . __('generic.update') . ' ' .
                \Carbon::now()->toDateTimeString() . "==========[/b]\n\n" . $request->content;
            $post->save();
        } else {
            $post = new Post;
            $post->content = $request->content;
            $post->topic_id = $topic->id;
            $post->user_id = \Auth::id();
            $post->save();
        }

        $topic->touch();

        return redirect(route_topic_show($topic));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $topic = $post->topic;

        if ($topic->solution_id === $id) {
            $topic->solution_id = null;
            $topic->save();
        }

        $post->delete();

        if ($topic->posts()->count() == 0) {
            $topic->delete();
            return route_forum_show($topic->forum);
        }

        return redirect()->back();
    }

    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
