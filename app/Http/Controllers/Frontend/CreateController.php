<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Validator;

use App\Post;
use App\Topic;

use App\Exceptions\UnexpectedException;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CreateController extends DashboardController
{
    public function topic(Request $request, string $forumId): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous(). '#scform')->withErrors($validator)->withInput();
        }

        $topic = new Topic;
        $topic->title = $request->title;
        $topic->slug = str_slug($topic->title);
        $topic->forum_id = $forumId;
        $topic->save();

        $topic->slug = unique_slug($topic->title, $topic->id);
        $topic->save();

        $post = new Post;
        $post->content = $request->content;
        $post->topic_id = $topic->id;
        $post->user_id = Auth::id();
        $post->save();

        return redirect(route('public.topic', ['topic' => $topic->slug]));
    }

    public function post(Request $request, string $topic): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required|min:5',
        ]);

        if ($validator->fails()) {
            return redirect()->to(app('url')->previous(). '#scform')->withErrors($validator)->withInput();
        }

        try {
            $topic = Topic::findOrFail($topic);
            $post = $topic->lastPost();

            if (Auth::id() === $post->user()->firstOrFail()->id) {
                $post->content .= "\n\n[b]========== DOPUNA " . \Carbon::now()->toDateTimeString() . "==========[/b]\n\n" . $request->content;
                $post->save();
            } else {
                $post = new Post;
                $post->content = $request->content;
                $post->topic_id = $topic->id;
                $post->user_id = Auth::id();
                $post->save();
            }

            $topic->touch();
            return redirect(route('public.topic', ['topic' => $topic->slug]) . '#post-' . $post->id);
        } catch (ModelNotFoundException $e) {
            throw new UnexpectedException($e);
        }
    }
}
