<?php

namespace App\Http\Controllers;

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
        foreach ($_POST["data"] ?? [] as $sectionId => $sectionData) {
            qUpdateCell("sections", $sectionId, "position", $sectionData["position"]);
            foreach ($sectionData["forums"] ?? [] as $rootIndex => $rootForum) {
                qUpdateCell("forums", $rootForum["id"], "parentId", "NULL");
                qUpdateCell("forums", $rootForum["id"], "sectionId", $sectionId);
                qUpdateCell("forums", $rootForum["id"], "position", $rootIndex + 1);
                foreach ($rootForum["children"] ?? [] as $childIndex => $childForum) {
                    qUpdateCell("forums", $childForum["id"], "parentId", $rootForum["id"]);
                    qUpdateCell("forums", $childForum["id"], "sectionId", $sectionId);
                    qUpdateCell("forums", $childForum["id"], "position", $childIndex + 1);
                }
            }
        }
        // TODO redirect to error page
    }
}
