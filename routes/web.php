<?php

Auth::routes();

Route::namespace('Website')
    ->name('website.')
    ->group(
        function() {
            Route::get('/', 'WebsiteController@index')->name('index');
            Route::get('directory/{slug}', 'WebsiteController@directory')->name('directory');

            Route::get('create', function () {
                return view('website.create');
            })->name('create');

            Route::resource('users', 'UsersController');
            Route::post('users/{id}/ban', 'UsersController@ban')->name('users.ban');

            Route::get('{token}/confirm', 'Auth\RegisterController@confirm')->name('confirm');
        }
    );

Route::namespace('Front')
    ->name('front.')
    ->group(
        function () {
            Route::get('/boards/{board_url}/', 'FrontController@index')->name('index');

            Route::resource('posts', 'PostsController');
            Route::resource('topics', 'TopicsController');
            Route::resource('forums', 'ForumsController');
            Route::resource('categories', 'CategoriesController');

            Route::post('forums/{id}/lock', 'ForumsController@lock')->name('forums.lock');
            Route::post('topics/{id}/lock', 'TopicsController@lock')->name('topics.lock');
            Route::post('topics/{id}/title', 'TopicsController@updateTitle')->name('topics.title');
            Route::post('topics/{id}/solution', 'TopicsController@updateSolution')->name('topics.solution');

            Route::post('posts/{id}/restore', 'PostsController@restore')->name('posts.restore');
            Route::post('topics/{id}/restore', 'TopicsController@restore')->name('topics.restore');
        }
    );

Route::namespace('Back')
    ->name('back.')
    ->prefix('admin/{board_name}')
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
