<?php

namespace App\Http\Controllers;

use Session;
use Exception;

use App\Forum;
use App\Category;
use App\Helpers\Common\Functions;
use App\Exceptions\SomeException;
use App\Exceptions\IdNotFoundException;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AjaxController extends Controller
{

    private function error(string $method, Exception $e, string $message = null)
    {
        $log = $method . ' ' . ($message ?? $e->getMessage());
        $this->logger->addRecord('error', $log);
        return response()->json([
            'status' => 'error',
            'message' => $log
        ], 500);
    }

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

                    if ($category->trashed()) {
                        $parentForum->delete();
                    }

                    foreach ($parentForumData['children'] ?? [] as $childIndex => $childForumData) {
                        $childForum = Forum::withTrashed()->find($childForumData['id']);
                        $childForum->update([
                            'parent_id' => $parentForumData['id'],
                            'category_id' => $categoryId,
                            'position' => $childIndex + 1
                        ]);

                        if ($parentForum->trashed()) {
                            $childForum->delete();
                        }
                    }

                }
            }
        } catch (Exception $e) {
            throw new SomeException($e);
        }
    }

    public function getParentCategory()
    {
        $id = request()->id;
        try {
            $forum = Forum::findOrFail($id);
            return response()->json(['category_id' => $forum->category_id]);
        } catch (ModelNotFoundException $e) {
            throw new IdNotFoundException($id, 'forums');
        }
    }
}
