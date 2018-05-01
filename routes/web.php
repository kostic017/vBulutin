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
    Route::get('confirm/{token}', 'Auth\RegisterController@confirm')->name('register.confirm');

    Route::get('', 'DashboardController@index')->name('public.index');

    // index
    Route::get('users/', 'DashboardController@users')->name('public.users');

    // show
    Route::get('topic/{topic}', 'DashboardController@topic')->name('public.topic');
    Route::get('forum/{forum}', 'DashboardController@forum')->name('public.forum');
    Route::get('profile/{profile}', 'DashboardController@profile')->name('public.profile');
    Route::get('category/{category}', 'DashboardController@category')->name('public.category');

    // create
    Route::post('create/post/{topic}', 'DashboardController@createPost')->name('public.post.create');
    Route::post('create/topic/{forum}', 'DashboardController@createTopic')->name('public.topic.create');
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
    Route::get('/{table}/{column}/{order}/sort', 'AjaxController@sort')->name('ajax.sort');

    Route::post('/positions/save', 'AjaxController@positions')->name('ajax.positions');
    Route::post('/getParentSection', 'AjaxController@getParentSection')->name('ajax.getParentSection');
});
