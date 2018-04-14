<?php

namespace App\Http\Controllers;

use Session;
use Exception;
use App\Forum;
use App\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{
    public function sort($table, $column, $order)
    {
        if (
            Schema::hasTable($table) &&
            Schema::hasColumn($table, $column) &&
            Functions::isEqualToAnyWord('ASC DESC', $order)
        ) {
            return response()->json(DB::table($table)->orderBy($column, $order)->pluck('id'));
        } else {
            $message = 'AJAXController@sort: ';
            $message .= '`{$table}.{$column}` ORDER BY {$order}';
            $this->$logger->addRecord('error', $message);
            Session::flash('error', 'messages.error');
        }
    }

    public function positions()
    {
        try {
            $data = request('data');
            foreach ($data as $categoryId => $category) {
                Category::where('id', $categoryId)->update(['position' => $category['position']]);
                foreach ($category['forums'] ?? [] as $parentIndex => $parentForum) {
                    Forum::where('id', $parentForum['id'])->update([
                        'parent_id' => null,
                        'category_id' => $categoryId,
                        'position' => $parentIndex + 1
                    ]);
                    foreach ($parentForum['children'] ?? [] as $childIndex => $childForum) {
                        Forum::where('id', $childForum['id'])->update([
                            'parent_id' => $parentForum['id'],
                            'category_id' => $categoryId,
                            'position' => $childIndex + 1
                        ]);
                    }
                }
            }
        } catch (Exception $e) {
            $message = 'AJAXController@positions: ';
            $message .= $e->getMessage();
            $this->logger->addRecord('error', $message);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred!'
            ], 500);
        }
    }

    public function getParentCategory() {
        $id = request()->id;
        if ($forum = Forum::where('id', $id)->first()) {
            return response()->json(['category_id' => $forum->category_id]);
        } else {
            $message = 'AJAXController@parentCategory: ';
            $message .= "Forum with id {$id} does not exists.";
            $this->logger->addRecord('error', $message);
            return response()->json([
                'status' => 'error',
                'message' => "Forum with id {$id} does not exists."
            ], 500);
        }
    }
}
