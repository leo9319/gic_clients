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
})->name('main');

Route::get('form', 'FormController@index')->name('home.form');
Route::post('form', 'FormController@store')->name('home.form.store');

Route::get('thank-you', function () {
    	return view('file.acknowledgement');
})->name('thanks');

Route::resource('file', 'FileController');
Route::get('myfile', 'FileController@myFile')->name('file.myfile');

Route::resource('task', 'TaskController')->middleware('role:admin,rm,counsellor');

Route::get('assign-task/{program_id}/{client}', 'TaskController@assignClient')->name('assign.task')->middleware('role:admin,rm,accountant,counsellor');
Route::get('assign-group-task/{program_id}/{client}', 'TaskController@assignGroupClient')->name('assign.group.task')->middleware('role:admin,rm,accountant');
Route::post('assign-task/{program_id}/{client_id}', 'TaskController@storeClientTasks')->name('store.client.task')->middleware('role:admin,rm,accountant');
Route::post('upload/{program_id}/{client_id}', 'TaskController@storeFiles')->name('upload.files');
Route::get('group/{program_id}', 'TaskController@taskGroup')->name('task.group');
Route::post('group/{client_id}/{program_id}', 'TaskController@taskGroupStore')->name('task.group.store');
Route::post('group-table/{program_id}', 'TaskController@taskTableGroupStore')->name('task.table.group.store');
Route::post('individual-tasks/{client_id}/{program_id}', 'TaskController@storeIndividualTasks')->name('task.add.individual');
Route::get('approval/{client_task_id}/{approval}', 'TaskController@approval')->name('task.approval');

Route::resource('client', 'ClientController')->middleware('role:admin,rm,accountant,operation,counsellor');
Route::get('mytasks/{program_id}/{client_id}', 'ClientController@mytasks')->name('client.mytasks');
Route::get('myprograms/{client_id}', 'ClientController@myPrograms')->name('client.myprograms');
Route::get('profile/{client_id}', 'ClientController@profile')->name('client.profile');
Route::get('view-tasks/{client_id}/{program_id}', 'ClientController@clientTasks')->name('client.tasks.all');
Route::get('programs/{client_id}', 'ClientController@programs')->name('client.programs');
Route::post('complete-group/{client_id}/{program_id}', 'ClientController@completeGroupStore')->name('client.group.complete.store');
Route::get('client/counsellor/{client_id}', 'ClientController@assignCounsellor')->name('client.counsellor');
Route::get('client/rm/{client_id}', 'ClientController@assignRm')->name('client.rm');
Route::post('client/counsellor/{client_id}', 'ClientController@assignCounsellorStore')->name('client.counsellor.store');
Route::post('client/rm/{client_id}', 'ClientController@assignRmStore')->name('client.rm.store');

Route::get('home', 'HomeController@home')->name('home');
Route::get('dashboard', 'HomeController@index')->name('dashboard');
Route::get('users', 'HomeController@users')->name('users')->middleware('role:admin');
Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::get('user-create', 'HomeController@createUser')->name('user.create');
Route::post('user-create', 'HomeController@storeUser')->name('user.store');
Route::post('register-staff', 'HomeController@customStaffRegister')->name('staff.store');


Route::get('test', 'FileController@test')->name('test');
Route::post('additional-info', 'FileController@storeAddition')->name('store.addition');
Route::post('additional-test', 'FileController@storeTest')->name('store.test');

Route::get('invoice', function () {
    return view('invoice.index');
});

Route::resource('rms', 'RmController');
Route::resource('gcalendar', 'gCalendarController');
Route::get('appointment/{client_id}', 'gCalendarController@setAppointment')->name('appointment.client');
Route::get('oauth', ['as' => 'oauthCallback', 'uses' => 'gCalendarController@oauth']);
// Route::get('getClientCounsellors', 'ClientController@getClientCounsellor');
Route::get('email/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendEmail')->name('email');
Route::get('sms/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendSMS')->name('sms');

Route::get('appointment/client/rm/{client_id}', 'AppointmentController@clientWithRm')->name('appointment.client.rm');
/*Route::get('appointment/client/counsellor/{client_id}', 'AppointmentController@clientWithCounsellor')->name('appointment.client.counsellor');
Route::get('testclient','TestController@index');*/