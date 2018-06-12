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

Route::group(['prefix' => '/'], function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::get('{token}/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');
});

Route::namespace('Website')
    ->name('website.')
    ->group(
        function() {
            Route::get('/', 'WebsiteController@index')->name('index');

            Route::resource('users', 'UsersController');
            Route::post('user/{id}/ban', 'UsersController@ban')->name('users.ban');
        }
    );

Route::get('/{board_name}/', 'Front\FrontController@index')->name('front.index');

Route::namespace('Front')
    ->name('front.')
    ->group(
        function () {
            Route::resource('posts', 'PostsController');
            Route::resource('topics', 'TopicsController');
            Route::resource('forums', 'ForumsController');
            Route::resource('categories', 'CategoriesController');

            Route::post('forum/{id}/lock', 'ForumsController@lock')->name('forums.lock');
            Route::post('topic/{id}/lock', 'TopicsController@lock')->name('topics.lock');
            Route::post('topic/{id}/title', 'TopicsController@updateTitle')->name('topics.title');
            Route::post('topic/{id}/solution', 'TopicsController@updateSolution')->name('topics.solution');

            Route::post('post/{id}/restore', 'PostsController@restore')->name('posts.restore');
            Route::post('topic/{id}/restore', 'TopicsController@restore')->name('topics.restore');
        }
    );

Route::namespace('Back')
    ->name('back.')
    ->prefix('{board_name}/admin')
    ->middleware('admin')
    ->group(
        function () {
            Route::get('/', function () {
                return redirect(route('back.categories.index'));
            })->name('index');

            Route::resource('forums', 'ForumsController');
            Route::resource('categories', 'CategoriesController');

            Route::get('reports/', 'ReportsController@index')->name('reports.index');
            Route::get('positions/', 'CategoriesController@positions')->name('positions');

            Route::post('forums/{id}/restore', 'ForumsController@restore')->name('forums.restore');
            Route::post('reports/{table}/generate', 'ReportsController@generate')->name('reports.generate');
            Route::post('categories/{id}/restore', 'CategoriesController@restore')->name('categories.restore');
        }
    );

Route::group(['prefix' => '/ajax'], function () {
    Route::post('quote', 'AjaxController@quote')->name('ajax.quote');
    Route::post('positions/save', 'AjaxController@positions')->name('ajax.positions');
});
