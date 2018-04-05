<?php

namespace App\Http\Controllers;

use App\Forum;
use App\Section;
use Edujugon\Log\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{
    public function sort($table, $column, $order) {
        if (
            Schema::hasTable($table) &&
            Schema::hasColumn($table, $column) &&
            $this->isEqualToAnyWord("ASC DESC", $order)
        ) {
            return response()->json(DB::table($table)->orderBy($column, $order)->pluck("id"));
        } else {
            Log::title("AJAX sort")
                ->level("error")
                ->line("Table name: `{$table}`")
                ->line("Colum name: `{$column}`")
                ->line("Order by: {$order}")
                ->write();
            // TODO redirect to error page
        }
    }

    public function savePositions() {
        $data = request("data");
        foreach ($data as $sectionId => $section) {
            Section::get($sectionId)->update(["position" => $section["position"]]);
            foreach ($section["forums"] ?? [] as $parentIndex => $parentForum) {
                Forum::get($parentForum["id"])->update([
                    "parentId" => null,
                    "sectionId" => $sectionId,
                    "position" => $parentIndex + 1
                ]);
                foreach ($parentForum["children"] ?? [] as $childIndex => $childForum) {
                    Forum::get($childForum["id"])->update([
                        "parentId" => $parentForum["id"],
                        "sectionId" => $sectionId,
                        "position" => $childIndex + 1
                    ]);
                }
            }
        }
    }
}
