<?php

namespace App\Http\Controllers;

use DB;
use Carbon;
use Schema;
use PdfReport;

class ReportsController extends Controller {

    public function index($board_address) {
        $users = Schema::getColumnListing('users');

        return view('admin.reports')
            ->with('categories', Schema::getColumnListing('categories'))
            ->with('forums', Schema::getColumnListing('forums'))
            ->with('topics', Schema::getColumnListing('topics'));
    }

    public function generate($board_address, $table) {
        $request = request();

        if (!$request->columns)
            return alert_redirect('error', url()->previous(), 'Odaberite bar jednu kolonu.');


        $columns = $request->columns;
        foreach ($columns as &$column)
            $column = "$table.$column";

        $data = array_combine($columns, $request->sort);
        $query = get_board($board_address)->{$table}()->select();

        foreach ($data as $key => $value)
            $query = $query->orderBy($key, $value);

        return PdfReport::of($table, [], $query, $request->columns)
            ->setCss([
                '.head-content' => 'border-width: 1px',
                'table tr td:first-child' => 'display: none',
                'table tr th:first-child' => 'display: none',
            ])
            ->download($table . '-report.' . Carbon::now()->format('d-m-y.H-i-s'));
    }
}
