<?php

namespace App\Http\Controllers\Front;

use Auth;
use App\Post;
use App\Topic;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PostsController extends FrontController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $topic_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($request, $topic_id)
    {
        $validator = \Validator::make($request->all(), [
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous(). '#scform')->withErrors($validator)->withInput();
        }

        $topic = Topic::findOrFail($topic_id);
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
        return redirect(route('front.topics.show', ['topic' => $topic->slug]) . '#post-' . $post->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Restore a soft-deleted model instance.
     *
     * @param  string  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        Post::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->back();
    }
}
