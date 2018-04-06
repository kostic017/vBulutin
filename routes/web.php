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

Auth::routes();

Route::get('/', function () {
    return view('public.index');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::get("/confirm/{token}", [
    "as" => "register.confirm",
    "uses" => "Auth\RegisterController@confirm"
]);

Route::group(["prefix" => "/admin", "middleware" => "admin"], function () {
    Route::get("/", function () {
        return view("admin.index");
    });
    Route::get("positions/", [
        "as" => "admin.positions",
        "uses" => "SectionsController@positions"
    ]);
    Route::resource("forums", "ForumsController");
    Route::resource("sections", "SectionsController");
});

Route::group(["prefix" => "/ajax"], function () {
    Route::get("/sort/{table}/{column}/{order}", [
        "as" => "ajax.sort",
        "uses" => "AjaxController@sort"
    ]);
    Route::post("/positions/save", [
        "as" => "ajax.positions",
        "uses" => "AjaxController@positions"
    ]);
});

Route::get('/js/lang.js', function () {
    $minutes = 10;
    $lang    = App::getLocale();
    $strings = Cache::remember($lang . '.lang.js', $minutes, function() use ($lang) {
        $strings = [];
        $files   = glob(resource_path('lang/' . $lang . '/*.php'));
        foreach ($files as $file) {
            $name           = basename($file, '.php');
            $strings[$name] = require $file;
        }
        return $strings;
    });
    return response('window.i18n = ' . json_encode($strings) . ';')
              ->header('Content-Type', 'text/javascript');
})->name('assets.lang');
