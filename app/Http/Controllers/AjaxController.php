<?php

namespace App\Http\Controllers;

use Exception;

use App\User;
use App\Post;
use App\Forum;
use App\Category;
use App\Exceptions\UnexpectedException;

use Illuminate\Database\Eloquent\ModelNotFoundException;

class AjaxController extends Controller
{

    public function positions()
    {
        try {
            $data = request('data');

            foreach ($data as $categoryId => $categoryData) {
                $category = Category::withTrashed()->find($categoryId);
                $category->update(['position' => $category['position']]);

                foreach ($categoryData['forums'] ?? [] as $parentIndex => $parentForumData) {
                    $parentForum = Forum::withTrashed()->find($parentForumData['id']);

                    $parentForum->update([
                        'parent_id' => null,
                        'category_id' => $categoryId,
                        'position' => $parentIndex + 1
                    ]);

                    foreach ($parentForumData['children'] ?? [] as $childIndex => $childForumData) {
                        $childForum = Forum::withTrashed()->find($childForumData['id']);
                        $childForum->update([
                            'parent_id' => $parentForumData['id'],
                            'category_id' => $categoryId,
                            'position' => $childIndex + 1
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            throw new UnexpectedException($e);
        }
    }

    public function quote()
    {
        $postId = request('postId');
        $post = Post::select('content', 'user_id')->findOrFail($postId);
        $user = User::select('username')->findOrFail($post->user_id);
        return "[quote={$user->username}]{$post->content}[/quote]";
    }

}
