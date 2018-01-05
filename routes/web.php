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

Route::get('/', 'PostsController@index');

//Route::get('/posts/{post}', 'PostsController@show');
Route::get('/posts/create', 'PostsController@create');
Route::post('/posts', 'PostsController@store');
// Route::get('/posts/create', 'PostsController@create');
// Route::get('/posts/create', 'PostsController@create');
// Route::get('/posts/create', 'PostsController@create');

// PATCH 
// DELETE
Route::get('/reconcile', function(){

});
Route::get('/reconcile/create', 'ReconcileController@create');
Route::post('/reconcile/store', 'ReconcileController@store');

Route::get('/reconcile', function () {
	$reconciles = DB::table('reconcile')->get();
    return view('reconcile', compact('reconciles'));
    // return $tasks;
});


Route::get('/reconcile/delete/{id}', function ($id) {

	$reconcile = DB::table('reconcile')->delete($id);
	// dd($reconcile);
    return "deleted!";//view('welcome', compact('reconcile'));
    // return $reconcile;
});