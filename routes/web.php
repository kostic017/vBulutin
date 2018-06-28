<?php

Auth::routes();
Route::get('{token}/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');

/*
|--------------------------------------------------------------------------
| Website
|--------------------------------------------------------------------------
*/

$website = function() {
    Route::group([
        'as' => 'website.',
        'namespace' => 'Website',
    ], function() {
        Route::get('/', 'WebsiteController@index')->name('index');
        Route::get('user/{username}/show', 'UsersController@show')->name('user.show');
        Route::get('directory/{slug}/show', 'DirectoriesController@show')->name('directory.show');

        Route::group([
            'middleware' => 'admin.master'
        ], function() {
            Route::resource('user', 'UsersController')->except(['show']);
            Route::resource('directory', 'DirectoriesController')->except(['show']);
        });
    });
};

Route::group(['domain' => 'www.' . config('app.domain')], $website);
Route::group(['domain' => config('app.domain')], $website);

/*
|--------------------------------------------------------------------------
| Board Public Area
|--------------------------------------------------------------------------
*/

Route::group([
    'as' => 'public.',
    'namespace' => 'Board\Publicus',
], function() {
    Route::resource('topic', 'TopicsController')->only(['store']);
    Route::resource('post', 'PostsController')->only(['store', 'destroy']);

    Route::post('forum/{id}/lock', 'ForumsController@lock')->name('forum.lock');
    Route::post('topic/{id}/lock', 'TopicsController@lock')->name('topic.lock');
    Route::post('topic/{id}/restore', 'TopicsController@restore')->name('topic.restore');
    Route::post('topic/{id}/title', 'TopicsController@update_title')->name('topic.title');
    Route::post('topic/{id}/solution', 'TopicsController@update_solution')->name('topic.solution');

    Route::post('post/{id}/restore', 'PostsController@restore')->name('post.restore');

    Route::group(['domain' => '{board_address}.' . config('app.domain')], function() {
        Route::get('/', 'BoardsController@show')->name('show');
        Route::get('category/{category_slug}', 'CategoriesController@show')->name('category.show');
        Route::get('forum/{forum_slug}', 'ForumsController@show')->name('forum.show');
        Route::get('topic/{topic_slug}', 'TopicsController@show')->name('topic.show');
    });
});

/*
|--------------------------------------------------------------------------
| Board Admin Area
|--------------------------------------------------------------------------
*/

Route::namespace('Board\Admin')
    ->name('admin.')
    ->prefix('admin/{board_address}')
    ->middleware('admin.board')
    ->group(
        function () {
            Route::get('/', function () {
                return redirect(route('admin.category.index'));
            })->name('index');

            Route::resource('forum', 'ForumsController');
            Route::resource('category', 'CategoriesController');

            Route::get('reports/', 'ReportsController@index')->name('reports.index');
            Route::get('positions/', 'CategoriesController@positions')->name('positions');

            Route::post('user/{id}/ban', 'UsersController@ban')->name('user.ban');
            Route::post('forum/{id}/restore', 'ForumsController@restore')->name('forum.restore');
            Route::post('report/{table}/generate', 'ReportsController@generate')->name('report.generate');
            Route::post('category/{id}/restore', 'CategoriesController@restore')->name('category.restore');
        }
    );


Route::name('admin.')
    ->group(function() {
        Route::resource('boards', 'Board\Admin\BoardsController');
        Route::get('website/directories/{directory_slug}/create', 'Board\Admin\BoardsController@create')->name('boards.create');
    });

/*
|--------------------------------------------------------------------------
| AJAX
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'ajax'], function () {
    Route::post('quote', 'AjaxController@quote')->name('ajax.quote');
    Route::post('positions/save', 'AjaxController@positions')->name('ajax.positions');
});
