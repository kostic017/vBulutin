<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get("/admin/", function() {
    return view("admin.index")->with("pageTitle", "Admin panel - Početna");
});

Route::get("/admin/table/{name}", function() {
    return view("admin.table")->with("pageTitle", "Table");
});
