<?php

namespace App\Http\Controllers\Back;

use DB;
use Schema;
use PdfReport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('board.admin.reports')
            ->with('categories', Schema::getColumnListing('categories'))
            ->with('forums', Schema::getColumnListing('forums'))
            ->with('topics', Schema::getColumnListing('topics'))
            ->with('users', Schema::getColumnListing('users'));
    }

    public function generate(Request $request, string $table)
    {
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
            ->download($table . '-report-' . \Carbon::now());
    }
}
