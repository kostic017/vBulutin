<?php

namespace App\Http\Controllers\Frontend;

use Auth;
use Validator;

use App\Post;
use App\Topic;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DeleteController extends DashboardController
{
    public function post(string $postId): RedirectResponse
    {
        $post = Post::findOrFail($postId);
        $topic = $post->topic()->first();

        if ($topic->solution_id === $postId) {
            $topic->solution_id = null;
            $topic->save();
        }

        $post->delete();
        return redirect()->back();
    }

    public function postRestore(string $postId): RedirectResponse
    {
        Post::onlyTrashed()->findOrFail($postId)->restore();
        return redirect()->back();
    }
}
