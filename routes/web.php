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
	// $sidemenus = array(
	// 	[
	// 		'displayname' => 'Apay',
	// 		'link' => '/apay'
	// 	],
	// 	[
	// 		'displayname' => 'bank',
	// 		'link' => '/bank'

	// 	]);

	return view('index',compact('sidemenus'));
});

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


Route::get('/bscompares', 'BSComparesController@index');
Route::get('/bscompares/accupdate/{ddate}/{accid}', 'BSComparesController@accupdate');



Route::get('/manualposts', 'ManualPostsController@index');
Route::get('/manualposts/create', 'ManualPostsController@create');
Route::post('/manualposts/store', 'ManualPostsController@store');
// Route::post('/manualposts/store', function(){
// 	dd("aa");
// });
Route::get('/manualposts/delete/{id}','ManualPostsController@destroy');
Route::get('/manualposts/edit/{id}','ManualPostsController@edit');
Route::post('/manualposts/update', 'ManualPostsController@update');
Route::get('/manualposts/post/{id}', 'ManualPostsController@manualpost');
Route::put('/manualposts/post/{id}', 'ManualPostsController@post');


Route::get('/postingrules', 'PostingRulesController@index');
Route::get('/postingrules/create', 'PostingRulesController@create');
Route::get('/postingrules/duplicate/{id}', 'PostingRulesController@duplicate');
Route::post('/postingrules/store', 'PostingRulesController@store');
Route::get('/postingrules/addwithdata', 'PostingRulesController@addwithdata');

Route::get('/bank', 'BankController@index');
Route::get('/bank/edit/{id}', 'BankController@edit');
Route::post('/bank/singlepost', 'BankController@singlepost');
Route::post('bank/post', 'BankController@post');

Route::get('/apay', 'ApayController@singlelist');
Route::get('/apay/edit/{id}', 'ApayController@edit');
Route::post('/apay/singlepost', 'ApayController@singlepost');
Route::get('apay/post', 'ApayController@post');
Route::get('/apay/showall', 'ApayController@index');

Route::get('/verify', 'VerifyController@index');
Route::get('/verify/bal', 'VerifyController@bal');

Route::get('/sidemenus', 'SidemenusController@index');
Route::get('/sidemenus/create', 'SidemenusController@create');
Route::get('/sidemenus/duplicate/{id}', 'SidemenusController@duplicate');
Route::get('/sidemenus/delete/{id}', 'SidemenusController@destroy');
Route::post('/sidemenus/store', 'SidemenusController@store');

Route::get('/fitransactions', 'FITransactionsController@index');
Route::get('/fitransactions/show/{id}', 'FITransactionsController@show');
