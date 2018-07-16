<?php

$r_domain = function() {
    Auth::routes();

    Route::resource('users', 'UsersController')->only(['edit', 'update']);
    Route::resource('posts', 'PostsController')->only(['store', 'destroy']);

    Route::get('/', 'WebsiteController@index')->name('website.index');
    Route::get('users', 'UsersController@index')->name('users.index.public');
    Route::get('users/{username}/show', 'UsersController@show')->name('users.show');
    Route::get('{id}/{token}/confirm', 'Auth\RegisterController@confirm')->name('register.confirm');
    Route::get('directories/{slug}/show', 'DirectoriesController@show')->name('directories.show');
    Route::get('directories/{slug}/create-board', 'BoardsController@create')->name('boards.create');

    Route::post('boards', 'BoardsController@store')->name('boards.store');
    Route::post('topics', 'TopicsController@store')->name('topics.store');
    Route::post('topics/{id}/title', 'TopicsController@update_title')->name('topics.title');
    Route::post('topics/{id}/solution', 'TopicsController@update_solution')->name('topics.solution');
    Route::post('posts/{id}/restore', 'PostsController@restore')->name('posts.restore');

    Route::group(['middleware' => 'admin.master'], function () {
        Route::resource('directories', 'DirectoriesController')->except(['index', 'show']);

        Route::post('users/{id}/banish', 'UsersController@banish')->name('users.banish');
        Route::post('users/{id}/master', 'UsersController@master')->name('users.master');
    });
};

$r_ajax = function() {
    Route::group(['prefix' => 'ajax'], function () {
        Route::post('quote', 'AjaxController@quote')->name('ajax.quote');
        Route::post('address', 'AjaxController@address')->name('ajax.address');
        Route::post('forums/positions/save', 'AjaxController@positions')->name('ajax.positions');
    });
};

$r_boards = function() {
    Route::group(['middleware' => ['viewshare.board', 'banned']], function () {
        Route::get('/', 'BoardsController@show')->name('boards.show');
        Route::get('categories/{slug}/show', 'CategoriesController@show')->name('categories.show.public');
        Route::get('forums/{slug}/show', 'ForumsController@show')->name('forums.show.public');
        Route::get('topics/{slug}/show', 'TopicsController@show')->name('topics.show.public');

        Route::group([
            'prefix' => 'admin',
            'middleware' => 'admin.board',
        ], function () {
            Route::resource('forums', 'ForumsController')->except(['show', 'create']);
            Route::resource('categories', 'CategoriesController')->except(['index', 'show']);

            Route::put('boards/', 'BoardsController@update')->name('boards.update');

            Route::get('/', 'BoardsController@edit')->name('admin.index');
            Route::get('categories/{slug}/show', 'CategoriesController@show_admin')->name('categories.show.admin');
            Route::get('forums/{force_section}/{force_id}/create', 'ForumsController@create')->name('forums.create');
            Route::get('forums/{slug}/show', 'ForumsController@show_admin')->name('forums.show.admin');
            Route::get('reports', 'ReportsController@index')->name('reports.index');
            Route::get('users/{page}', 'UsersController@index_admin')->name('users.index.admin');

            Route::post('forums/{id}/lock', 'ForumsController@lock')->name('forums.lock');
            Route::post('topics/{id}/lock', 'TopicsController@lock')->name('topics.lock');
            Route::post('reports/{table}/generate', 'ReportsController@generate')->name('reports.generate');
            Route::post('forums/{id}/restore', 'ForumsController@restore')->name('forums.restore');
            Route::post('categories/{id}/restore', 'CategoriesController@restore')->name('categories.restore');
            Route::post('users/{id}/ban', 'UsersController@ban')->name('users.ban');
            Route::post('users/{id}/admin', 'UsersController@admin')->name('users.admin');
        });
    });
};

if (config('custom.subdomains')) {
    $domain = config('custom.domain');
    Route::group(['domain' => $domain], $r_domain);
    Route::group(['domain' => '{board_address}.' . $domain], function() use ($r_ajax, $r_boards) {
        $r_ajax();
        $r_boards();
    });
} else {
    $r_domain();
    $r_ajax();
    Route::group(['prefix' => 'boards/{board_address}'], $r_boards);
}
