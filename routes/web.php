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

    // index
    Route::get('', 'Frontend\IndexController@index')->name('public.index');
    Route::get('users/', 'Frontend\IndexController@users')->name('public.users');

    // show
    Route::get('topic/{topic}', 'Frontend\ShowController@topic')->name('public.topic');
    Route::get('forum/{forum}', 'Frontend\ShowController@forum')->name('public.forum');
    Route::get('category/{category}', 'Frontend\ShowController@category')->name('public.category');
    Route::get('profile/{profile}/show', 'Frontend\ShowController@profile')->name('public.profile.show');

    // create & store
    Route::post('create/post/{topic}', 'Frontend\CreateController@post')->name('public.post.create');
    Route::post('create/topic/{forum}', 'Frontend\CreateController@topic')->name('public.topic.create');

    // edit
    Route::get('profile/{profile}/edit', 'Frontend\EditController@profile')->name('public.profile.edit');
    Route::post('topic/{topic}/title', 'Frontend\EditController@topicTitle')->name('public.topic.title');
    Route::post('topic/{topic}/solution', 'Frontend\EditController@topicSolution')->name('public.topic.solution');

    // update
    Route::get('profile/{profile}/update', 'Frontend\UpdateController@profile')->name('public.profile.update');
});

Route::group(['prefix' => '/admin', 'middleware' => 'admin'], function () {
    Route::get('/', function () {
        return view('admin.index');
    })->name('admin.index');

    Route::get('positions/', 'Admin\CategoriesController@positions')->name('admin.positions');

    Route::post('forums/{forum}/restore', 'Admin\ForumsController@restore')->name('forums.restore');
    Route::post('categories/{category}/restore', 'Admin\CategoriesController@restore')->name('categories.restore');

    Route::resource('forums', 'Admin\ForumsController');
    Route::resource('categories', 'Admin\CategoriesController');
});

Route::group(['prefix' => '/ajax'], function () {
    Route::post('quote', 'AjaxController@quote')->name('ajax.quote');
    Route::post('positions/save', 'AjaxController@positions')->name('ajax.positions');
});
