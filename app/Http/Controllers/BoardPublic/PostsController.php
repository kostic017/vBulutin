<?php

namespace App\Http\Controllers\BoardPublic;

use Auth;
use App\Post;
use App\Topic;
use Illuminate\Http\Request;

class PostsController extends FrontController
{
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous(). '#scform')->withErrors($validator)->withInput();
        }

        $topic = Topic::findOrFail($request->topic_id);
        $post = $topic->lastPost();

        if (Auth::id() === $post->user()->firstOrFail()->id) {
            $post->content .= "\n\n[b]========== " . __('generic.update') . ' ' .
                \Carbon::now()->toDateTimeString() . "==========[/b]\n\n" . $request->content;
            $post->save();
        } else {
            $post = new Post;
            $post->content = $request->content;
            $post->topic_id = $topic->id;
            $post->user_id = Auth::id();
            $post->save();
        }

        $topic->touch();
        return redirect(route('board.public.topics.show', ['topic' => $topic->slug]) . '#post-' . $post->id);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $topic = $post->topic()->first();

        if ($topic->solution_id === $id) {
            $topic->solution_id = null;
            $topic->save();
        }

        $post->delete();
        return redirect()->back();
    }

    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
