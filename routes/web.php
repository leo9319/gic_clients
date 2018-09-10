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




Route::resources([

	'file' => 'FileController',
	'step' => 'StepController',
	'task' => 'TaskController',
	'client' => 'ClientController',
	'rms' => 'RmController',
	'counselors' => 'CounselorController',
	'program' => 'ProgramController',
	'payment' => 'PaymentController',

]);





Route::get('test', 'FileController@test')->name('test');
Route::get('myfile', 'FileController@myFile')->name('file.myfile');

Route::post('additional-info', 'FileController@storeAddition')->name('store.addition');
Route::post('additional-test', 'FileController@storeTest')->name('store.test');





Route::get('task/delete/{task_id}', 'TaskController@deleteTask')->name('delete.task');
Route::get('download/{file_name}', 'TaskController@downloadFile')->name('download');
Route::get('assign-task/{program_id}/{client}', 'TaskController@assignClient')->name('assign.task')->middleware('role:admin,rm,accountant,counsellor');
Route::get('assign-group-task/{program_id}/{client}', 'TaskController@assignGroupClient')->name('assign.group.task')->middleware('role:admin,rm,accountant');
Route::post('assign-task/{program_id}/{client_id}', 'TaskController@storeClientTasks')->name('store.client.task')->middleware('role:admin,rm,accountant');
Route::get('group/{program_id}', 'TaskController@taskGroup')->name('task.group');

Route::get('approval/{client_task_id}/{approval}', 'TaskController@approval')->name('task.approval');
Route::get('approval/spouse/{spouse_task_id}/{approval}', 'TaskController@spouseApproval')->name('task.spouse.approval');
Route::post('task/edit', 'TaskController@editTask')->name('edit.task');
Route::post('upload/{program_id}/{client_id}', 'TaskController@storeFiles')->name('upload.files');
Route::post('group/{client_id}/{program_id}', 'TaskController@taskGroupStore')->name('task.group.store');
Route::post('group-table/{program_id}', 'TaskController@taskTableGroupStore')->name('task.table.group.store');
Route::post('update/client/task', 'TaskController@updateClientTask')->name('update.client.task');
Route::post('update/spouse/task', 'TaskController@updateSpouseTask')->name('update.spouse.task');
Route::post('individual-tasks/{client_id}/{program_id}', 'TaskController@storeIndividualTasks')->name('task.add.individual');





Route::get('mytasks/{step_id}/{client_id}', 'ClientController@mytasks')->name('client.mytasks');
Route::get('client/action/{client_id}', 'ClientController@action')->name('client.action');
Route::get('spousetasks/{step_id}/{client_id}', 'ClientController@spousetasks')->name('spouse.mytasks');
Route::get('mysteps/{program_id}/{client_id}', 'ClientController@mySteps')->name('client.steps');
Route::get('spousesteps/{program_id}/{client_id}', 'ClientController@spouseSteps')->name('spouse.steps');
Route::get('myprograms/{client_id}', 'ClientController@myPrograms')->name('client.myprograms');
Route::get('spouseprograms/{client_id}', 'ClientController@spousePrograms')->name('spouse.myprograms');
Route::get('profile/{client_id}', 'ClientController@profile')->name('client.profile');
Route::get('client/counsellor/{client_id}', 'ClientController@assignCounsellor')->name('client.counsellor');
Route::get('client/rm/{client_id}', 'ClientController@assignRm')->name('client.rm');

Route::post('myprograms/{client_id}', 'ClientController@storeClientProgram')->name('client.myprograms.store');
Route::post('complete-group/{client_id}/{program_id}', 'ClientController@completeGroupStore')->name('client.group.complete.store');
Route::post('client/counsellor/{client_id}', 'ClientController@assignCounsellorStore')->name('client.counsellor.store');
Route::post('client/rm/{client_id}', 'ClientController@assignRmStore')->name('client.rm.store');

Route::post('client/step/{program_id}/{client_id}', 'ClientController@storeSteps')->name('client.step.store');
Route::post('client/individual/step/{step_id}/{client_id}', 'ClientController@storeIndividualTask')->name('client.task.individual.store');
Route::post('spouse/individual/step/{step_id}/{client_id}', 'ClientController@storeSpouseIndividualTask')->name('spouse.task.individual.store');





Route::get('home', 'HomeController@home')->name('home');
Route::get('dashboard', 'HomeController@index')->name('dashboard');
Route::get('users', 'HomeController@users')->name('users')->middleware('role:admin');
Route::get('user-create', 'HomeController@createUser')->name('user.create');

Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::post('user-create', 'HomeController@storeUser')->name('user.store');
Route::post('register-staff', 'HomeController@customStaffRegister')->name('staff.store');





Route::get('appointments', 'AppointmentController@index')->name('appointment.index');
Route::get('appointments/rm', 'AppointmentController@setRmAppointment')->name('appointment.rm.appointment');
Route::get('appointments/counselor', 'AppointmentController@setCounselorAppointment')->name('appointment.counselor.appointment');
Route::get('client/appointments/{client_id}', 'AppointmentController@clientAppointment')->name('client.appointment');




Route::get('program/delete/{program_id}', 'ProgramController@deleteProgram')->name('delete.program');
Route::get('findClientProgram', 'ProgramController@clientProgram');
Route::get('findProgramStep', 'ProgramController@programStep');
Route::get('getIndividualClientProgram', 'ProgramController@individualClientProgram');

Route::post('program/edit', 'ProgramController@editProgram')->name('edit.program');




Route::get('target/rm', 'TargetController@rm')->name('target.rm');
Route::get('target/counselor', 'TargetController@counselor')->name('target.counselor');
Route::get('set/target/{user_id}', 'TargetController@setTarget')->name('set.target');

Route::post('target/{user_id}', 'TargetController@storeTarget')->name('store.target');




Route::get('comment/task/{client_task_id}', 'CommentController@task')->name('comment.tasks');
Route::get('comment/spouse/task/{spouse_task_id}', 'CommentController@spouseTask')->name('comment.spouse.tasks');
Route::get('comment/appointment/{client_appointment_id}', 'CommentController@appointment')->name('comment.appointments');

Route::post('comment/task/{client_task_id}', 'CommentController@taskCommentStore')->name('comment.tasks.store');
Route::post('comment/spouse/task/{spouse_task_id}', 'CommentController@spouseTaskCommentStore')->name('comment.spouse.tasks.store');
Route::post('comment/appointment/{client_appointment_id}', 'CommentController@appointmentCommentStore')->name('comment.appointment.store');




Route::get('sms/{client_id}', 'TextController@smsIndex')->name('sms.index');
Route::get('email/{client_id}', 'TextController@emailIndex')->name('email.index');

Route::post('email/{client_id}', 'TextController@sendEmail')->name('email.send');
Route::post('sms/{client_id}', 'TextController@sendSms')->name('sms.send');




Route::get('form', 'FormController@index')->name('home.form');
Route::post('form', 'FormController@store')->name('home.form.store');




Route::get('history/payment', 'PaymentController@paymentHistory')->name('payment.history');



Route::get('invoice/opening/{client_id}', 'InvoiceController@opening')->name('invoice.opening');



Route::get('myclients/{user_id}', 'UserController@myclients')->name('user.clients');



Route::get('step/delete/{step_id}', 'StepController@delete')->name('step.delete');



Route::get('/', function () {
    return view('welcome');
})->name('main');




Route::get('thank-you', function () {
    	return view('file.acknowledgement');
})->name('thanks');




Route::resource('gcalendar', 'gCalendarController');
Route::get('appointment/{client_id}', 'gCalendarController@setAppointment')->name('appointment.client');
Route::get('oauth', 'gCalendarController@oauth')->name('oauthCallback');
Route::get('email/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendEmail')->name('email');
Route::get('sms/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendSMS')->name('sms');