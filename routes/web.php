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

Auth::routes();

Route::get('/', function () {
    return view('welcome');
});

Route::get('thank-you', function () {
    	return view('file.acknowledgement');
})->name('thanks');

Route::resource('file', 'FileController');

Route::resource('task', 'TaskController')->middleware('role:rm');
Route::get('assign-task/{client}', 'TaskController@assignClient')->name('assign.task')->middleware('role:accountant');
Route::post('assign-task/{client_id}', 'TaskController@storeClientTasks')->name('store.client.task')->middleware('role:accountant');

Route::resource('client', 'ClientController')->middleware('role:accountant');

Route::get('home', 'HomeController@home')->name('home');
Route::get('dashboard', 'HomeController@index')->name('dashboard');
Route::get('users', 'HomeController@users')->name('users')->middleware('role:admin');
Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::get('user-create', 'HomeController@createUser')->name('user.create');
Route::post('user-create', 'HomeController@storeUser')->name('user.store');

Route::get('test', 'FileController@test')->name('test');
Route::post('additional-info', 'FileController@storeAddition')->name('store.addition');
Route::post('additional-test', 'FileController@storeTest')->name('store.test');