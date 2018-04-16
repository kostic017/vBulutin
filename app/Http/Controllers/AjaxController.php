<?php

namespace App\Http\Controllers;

use Session;
use Exception;
use App\Forum;
use App\Category;
use App\Helpers\Common\Functions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{

    private function error(string $method, Exception $e, string $message = null) {
        $log = $method . ' ' . ($message ?? $e->getMessage());
        $this->logger->addRecord('error', $log);
        return response()->json([
            'status' => 'error',
            'message' => $log
        ], 500);
    }

    public function sort($table, $column, $order)
    {
       // TODO
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
            return $this->error(__METHOD__, $e);
        }
    }

    public function getParentCategory() {
        $id = request()->id;
        if ($forum = Forum::where('id', $id)->first()) {
            return response()->json(['category_id' => $forum->category_id]);
        } else {
            return $this->error(__METHOD__, $e, "Forum with id {$id} does not exists.");
        }
    }
}
