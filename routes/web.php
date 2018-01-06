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

// Route::get('/tasks', 'TasksController@index');

// Route::get('/tasks/{task}','TasksController@show');

Route::get('/', function(){
	return view('index');
});


//Route::get('/posts/{post}', 'PostsController@show');
Route::get('/posts/create', 'PostsController@create');
Route::post('/posts', 'PostsController@store');
// Route::get('/posts/create', 'PostsController@create');
// Route::get('/posts/create', 'PostsController@create');
// Route::get('/posts/create', 'PostsController@create');

// PATCH 
// DELETE
Route::get('/reconcile', 'ReconcilesController@index');
Route::get('/reconcile/create', 'ReconcilesController@create');
Route::get('/reconcile/add', 'ReconcilesController@add');
Route::post('/reconcile/store', 'ReconcilesController@store');
Route::get('/reconcile/delete/{id}','ReconcilesController@destroy');


// make controller
// make migration

Route::get('/bscheckpoint', 'BSCheckPointsController@index');
Route::get('/bscheckpoint/create', 'BSCheckPointsController@create');
Route::get('/bscheckpoint/add', 'BSCheckPointsController@add');
Route::post('/bscheckpoint/store', 'BSCheckPointsController@store');
Route::get('/bscheckpoint/delete/{id}','BSCheckPointsController@destroy');
