<?php

namespace App\Http\Controllers;

use DB;
use Carbon;
use Schema;
use PdfReport;

class ReportsController extends Controller {

    public function index($board_address) {
        return view('admin.reports')
            ->with('categories', Schema::getColumnListing('categories'))
            ->with('forums', Schema::getColumnListing('forums'))
            ->with('topics', Schema::getColumnListing('topics'))
            ->with('users', Schema::getColumnListing('users'));
    }

    public function generate($board_address, $table) {
        $request = request();

        if (!$request->columns) {
            return alert_redirect('error', url()->previous(), 'Odaberite bar jednu kolonu.');
        }

        $data = array_combine($request->columns, $request->sort);

        $query = DB::table($table)->select($request->columns);
        foreach ($data as $key => $value) {
            $query = $query->orderBy($key, $value);
        }

        return PdfReport::of($table, [], $query, $request->columns)
            ->setCss([
                '.head-content' => 'border-width: 1px',
                'table tr td:first-child' => 'display: none',
                'table tr th:first-child' => 'display: none',
            ])
            ->download($table . '-report-' . Carbon::now());
    }
}
