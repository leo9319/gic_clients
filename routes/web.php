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

Route::get('/invoice', function () {
    return view('invoice.test');
});

Route::get('form', 'FormController@index')->name('home.form');
Route::post('form', 'FormController@store')->name('home.form.store');

Route::get('thank-you', function () {
    	return view('file.acknowledgement');
})->name('thanks');

Route::resource('file', 'FileController');
Route::resource('step', 'StepController');
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
Route::post('update/client/task', 'TaskController@updateClientTask')->name('update.client.task');

Route::resource('client', 'ClientController')->middleware('role:admin,rm,accountant,operation,counselor,backend');
Route::get('mytasks/{step_id}/{client_id}', 'ClientController@mytasks')->name('client.mytasks');
Route::get('mysteps/{program_id}/{client_id}', 'ClientController@mySteps')->name('client.steps');
Route::get('myprograms/{client_id}', 'ClientController@myPrograms')->name('client.myprograms');
Route::get('profile/{client_id}', 'ClientController@profile')->name('client.profile');
Route::post('complete-group/{client_id}/{program_id}', 'ClientController@completeGroupStore')->name('client.group.complete.store');
Route::get('client/counsellor/{client_id}', 'ClientController@assignCounsellor')->name('client.counsellor');
Route::get('client/rm/{client_id}', 'ClientController@assignRm')->name('client.rm');
Route::post('client/counsellor/{client_id}', 'ClientController@assignCounsellorStore')->name('client.counsellor.store');
Route::post('client/rm/{client_id}', 'ClientController@assignRmStore')->name('client.rm.store');
Route::get('client/action/{client_id}', 'ClientController@action')->name('client.action');
Route::post('client/step/{program_id}/{client_id}', 'ClientController@storeSteps')->name('client.step.store');
Route::post('client/individual/step/{step_id}/{client_id}', 'ClientController@storeIndividualTask')->name('client.task.individual.store');

Route::get('home', 'HomeController@home')->name('home');
Route::get('dashboard', 'HomeController@index')->name('dashboard');
Route::get('users', 'HomeController@users')->name('users')->middleware('role:admin');
Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::get('user-create', 'HomeController@createUser')->name('user.create');
Route::post('user-create', 'HomeController@storeUser')->name('user.store');
Route::post('register-staff', 'HomeController@customStaffRegister')->name('staff.store');


Route::get('test', 'FileController@test')->name('test');
Route::post('additional-info', 'FileController@storeAddition')->name('store.addition');
Route::post('additional-test', 'FileController@storeTest')->name('store.test');

Route::get('invoice/opening/{client_id}', 'InvoiceController@opening')->name('invoice.opening');

Route::resource('rms', 'RmController');
Route::resource('counselors', 'CounselorController');

Route::get('appointments', 'AppointmentController@index')->name('appointment.index');
Route::get('appointments/rm', 'AppointmentController@setRmAppointment')->name('appointment.rm.appointment');
Route::get('appointments/counselor', 'AppointmentController@setCounselorAppointment')->name('appointment.counselor.appointment');
Route::get('client/appointments/{client_id}', 'AppointmentController@clientAppointment')->name('client.appointment');

Route::resource('program', 'ProgramController');

Route::get('target/rm', 'TargetController@rm')->name('target.rm');
Route::get('target/counselor', 'TargetController@counselor')->name('target.counselor');
Route::get('set/target/{user_id}', 'TargetController@setTarget')->name('set.target');
Route::post('target/{user_id}', 'TargetController@storeTarget')->name('store.target');

Route::resource('gcalendar', 'gCalendarController');
Route::get('appointment/{client_id}', 'gCalendarController@setAppointment')->name('appointment.client');
Route::get('oauth', ['as' => 'oauthCallback', 'uses' => 'gCalendarController@oauth']);
Route::get('email/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendEmail')->name('email');
Route::get('sms/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendSMS')->name('sms');

Route::get('myclients/{user_id}', 'UserController@myclients')->name('user.clients');

Route::get('comment/task/{client_task_id}', 'CommentController@task')->name('comment.tasks');
Route::get('comment/appointment/{client_appointment_id}', 'CommentController@appointment')->name('comment.appointments');

Route::post('comment/task/{client_task_id}', 'CommentController@taskCommentStore')->name('comment.tasks.store');
Route::post('comment/appointment/{client_appointment_id}', 'CommentController@appointmentCommentStore')->name('comment.appointment.store');

Route::get('sms/{client_id}', 'TextController@smsIndex')->name('sms.index');
Route::post('sms/{client_id}', 'TextController@sendSms')->name('sms.send');
Route::get('email/{client_id}', 'TextController@emailIndex')->name('email.index');
Route::post('email/{client_id}', 'TextController@sendEmail')->name('email.send');

Route::get('download/{file_name}', 'TaskController@downloadFile')->name('download');