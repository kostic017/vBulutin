<?php

Auth::routes();
Route::get('/', 'Website\WebsiteController@index')->name('index');
Route::get('{token}/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');

Route::namespace('Website')
    ->name('website.')
    ->prefix('website')
    ->group(
        function() {
            Route::resource('users', 'UsersController');
            Route::resource('directories', 'DirectoriesController');
        }
    );

Route::namespace('Board\Publicus')
    ->name('public.')
    ->group(
        function () {
            Route::resource('posts', 'PostsController');
            Route::resource('forums', 'ForumsController');
            Route::resource('topics', 'TopicsController');
            Route::resource('categories', 'CategoriesController');

            Route::get('{url}', 'BoardsController@show')->name('show');

            Route::post('posts/{id}/restore', 'PostsController@restore')->name('posts.restore');
            Route::post('forums/{id}/lock', 'ForumsController@lock')->name('forums.lock');
            Route::post('topics/{id}/lock', 'TopicsController@lock')->name('topics.lock');
            Route::post('topics/{id}/title', 'TopicsController@updateTitle')->name('topics.title');
            Route::post('topics/{id}/restore', 'TopicsController@restore')->name('topics.restore');
            Route::post('topics/{id}/solution', 'TopicsController@updateSolution')->name('topics.solution');
        }
    );

Route::namespace('Board\Admin')
    ->name('admin.')
    ->prefix('admin/{board_url}')
    ->middleware('admin')
    ->group(
        function () {
            Route::get('/', function () {
                return redirect(route('admin.categories.index'));
            })->name('index');

            Route::resource('forums', 'ForumsController');
            Route::resource('categories', 'CategoriesController');

            Route::get('reports/', 'ReportsController@index')->name('reports.index');
            Route::get('positions/', 'CategoriesController@positions')->name('positions');

            Route::post('users/{id}/ban', 'UsersController@ban')->name('users.ban');
            Route::post('forums/{id}/restore', 'ForumsController@restore')->name('forums.restore');
            Route::post('reports/{table}/generate', 'ReportsController@generate')->name('reports.generate');
            Route::post('categories/{id}/restore', 'CategoriesController@restore')->name('categories.restore');
        }
    );


Route::name('admin.')
    ->group(function() {
        Route::resource('boards', 'Board\Admin\BoardsController');
        Route::get('{directory_slug}/create', 'Board\Admin\BoardsController@create')->name('boards.create');
    });

Route::group(['prefix' => '/ajax'], function () {
    Route::post('quote', 'AjaxController@quote')->name('ajax.quote');
    Route::post('positions/save', 'AjaxController@positions')->name('ajax.positions');
});
