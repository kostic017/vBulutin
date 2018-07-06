<?php

namespace App\Http\Controllers;

use Exception;

use App\User;
use App\Post;
use App\Forum;
use App\Category;

class AjaxController extends Controller {

    public function positions() {
        $data = request('data');

        foreach ($data as $category_id => $category_data) {
            $category = Category::withTrashed()->find($category_id);
            $category->update(['position' => $category['position']]);

            foreach ($category_data['forums'] ?? [] as $parent_index => $parent_forum_data) {
                $parent_forum = Forum::withTrashed()->find($parent_forum_data['id']);
                $parent_forum->update([
                    'parent_id' => null,
                    'category_id' => $category_id,
                    'position' => $parent_index + 1
                ]);

                foreach ($parent_forum_data['children'] ?? [] as $child_index => $child_forum_data) {
                    $child_forum = Forum::withTrashed()->find($child_forum_data['id']);
                    $child_forum->update([
                        'parent_id' => $parent_forum_data['id'],
                        'category_id' => $category_id,
                        'position' => $child_index + 1
                    ]);
                }
            }
        }
    }

    public function quote() {
        $postId = request('postId');
        $post = Post::select('content', 'user_id')->findOrFail($postId);
        $user = User::select('username')->findOrFail($post->user_id);
        return "[quote={$user->username}]{$post->content}[/quote]";
    }

}
