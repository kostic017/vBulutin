<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the 'web' middleware group. Now create something great!
|
*/

Auth::routes();

Route::get('/', [
    'as' => 'public.index',
    'uses' => 'DashboardController@index',
]);

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/confirm/{token}', [
    'as' => 'register.confirm',
    'uses' => 'Auth\RegisterController@confirm'
]);

Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
    Route::get('/', function () {
        return view('admin.index');
    });

    Route::get('positions/', [
        'as' => 'admin.positions',
        'uses' => 'Sections\CategoriesController@positions'
    ]);

    Route::post('forums/{forum}/restore', [
        'as' => 'forums.restore',
        'uses' => 'Sections\ForumsController@restore'
    ]);

    Route::post('categories/{category}/restore', [
        'as' => 'categories.restore',
        'uses' => 'Sections\CategoriesController@restore'
    ]);

    Route::resource('forums', 'Sections\ForumsController');
    Route::resource('categories', 'Sections\CategoriesController');
});

Route::group(['prefix' => '/ajax'], function () {
    Route::get('/{table}/{column}/{order}/sort', [
        'as' => 'ajax.sort',
        'uses' => 'AjaxController@sort'
    ]);

    Route::post('/positions/save', [
        'as' => 'ajax.positions',
        'uses' => 'AjaxController@positions'
    ]);

    Route::post('/getParentSection', [
        'as' => 'ajax.getParentSection',
        'uses' => 'AjaxController@getParentSection'
    ]);
});
