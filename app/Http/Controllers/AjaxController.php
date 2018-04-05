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
        }
    }
}
