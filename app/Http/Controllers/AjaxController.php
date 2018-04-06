<?php

namespace App\Http\Controllers;

use Session;
use App\Forum;
use App\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{
    public function sort($table, $column, $order)
    {
        if (
            Schema::hasTable($table) &&
            Schema::hasColumn($table, $column) &&
            $this->isEqualToAnyWord('ASC DESC', $order)
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
            foreach ($data as $sectionId => $section) {
                Section::where('id', $sectionId)->update(['position' => $section['position']]);
                foreach ($section['forums'] ?? [] as $parentIndex => $parentForum) {
                    Forum::where('id', $parentForum['id'])->update([
                        'parent_id' => null,
                        'section_id' => $sectionId,
                        'position' => $parentIndex + 1
                    ]);
                    foreach ($parentForum['children'] ?? [] as $childIndex => $childForum) {
                        Forum::where('id', $childForum['id'])->update([
                            'parent_id' => $parentForum['id'],
                            'section_id' => $sectionId,
                            'position' => $childIndex + 1
                        ]);
                    }
                }
            }
        } catch (\Exception $e) {
            $message = 'AJAXController@positions: ';
            $message .= $e->getMessage();
            $this->logger->addRecord('error', $message);
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred!'
            ], 500);
        }
    }
}
