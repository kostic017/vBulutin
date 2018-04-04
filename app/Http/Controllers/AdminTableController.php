<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tableName)
    {
        if (Schema::hasTable($tableName)) {
            $rows = DB::table($tableName)->get();
            return view("admin.table")->with(compact("tableName", "rows"));
        } else {
            return view("admin.errors.404")->with("message", "Tabela `{$tableName}` ne postoji u bazi.");
        }
    }
}
