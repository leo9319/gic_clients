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



Route::get('/counselors/remove/client/{counselor_client_id}', 'CounselorController@removeClient')->name('remove.counselor.client');
Route::get('/rms/remove/client/{rm_client_id}', 'RmController@removeClient')->name('remove.rm.client');





Route::get('test', 'TestController@index')->name('test');
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

Route::get('task/user-tasks/{user_id}', 'TaskController@userTasks')->name('task.user.tasks');
Route::get('task/update/status/{task_id}/{status}', 'TaskController@updateTaskStatus')->name('task.update.status');

Route::post('task/edit', 'TaskController@editTask')->name('edit.task');
Route::post('upload/{program_id}/{client_id}', 'TaskController@storeFiles')->name('upload.files');
Route::post('group/{client_id}/{program_id}', 'TaskController@taskGroupStore')->name('task.group.store');
Route::post('group-table/{program_id}', 'TaskController@taskTableGroupStore')->name('task.table.group.store');
Route::post('update/client/task', 'TaskController@updateClientTask')->name('update.client.task');
Route::post('update/spouse/task', 'TaskController@updateSpouseTask')->name('update.spouse.task');
Route::post('individual-tasks/{client_id}/{program_id}', 'TaskController@storeIndividualTasks')->name('task.add.individual');
Route::post('task/upload/file', 'TaskController@uploadFile')->name('task.upload.file');

Route::get('getStep', 'TaskController@getStep');
Route::post('task/user/create', 'TaskController@addUserTask')->name('task.user.create');

Route::get('task/client/user-task/{client_id}/{user_id}', 'TaskController@userTask')->name('task.client.user');

Route::get('task/approve/{task_id}/{approval}', 'TaskController@taskApprove')->name('task.approve');






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
Route::get('client/assigned/counselor/{client_id}', 'ClientController@assingedCounselor')->name('client.assigned.counselor');
Route::get('client/assigned/rm/{client_id}', 'ClientController@assingedRm')->name('client.assigned.rm');

Route::post('myprograms/{client_id}', 'ClientController@storeClientProgram')->name('client.myprograms.store');
Route::post('complete-group/{client_id}/{program_id}', 'ClientController@completeGroupStore')->name('client.group.complete.store');
Route::post('client/counsellor/{client_id}', 'ClientController@assignCounsellorStore')->name('client.counsellor.store');
Route::post('client/rm/{client_id}', 'ClientController@assignRmStore')->name('client.rm.store');

Route::post('client/step/{program_id}/{client_id}', 'ClientController@storeSteps')->name('client.step.store');
Route::post('client/individual/step/{step_id}/{client_id}', 'ClientController@storeIndividualTask')->name('client.task.individual.store');
Route::post('spouse/individual/step/{step_id}/{client_id}', 'ClientController@storeSpouseIndividualTask')->name('spouse.task.individual.store');
Route::post('client/delete', 'ClientController@clientDestroy')->name('client.destroy');
Route::get('client/edit/ind/{client_id}', 'ClientController@clientEdit')->name('client.edit.ind');
Route::post('client/update/ind', 'ClientController@clientUpdate')->name('client.update.ind');
Route::get('getClientName', 'ClientController@getClientName');








Route::get('home', 'HomeController@home')->name('home');
Route::get('dashboard', 'HomeController@index')->name('dashboard');
Route::get('users', 'HomeController@users')->name('users')->middleware('role:admin');

Route::get('user-create', 'HomeController@createUser')->name('user.create');
Route::post('user-create', 'HomeController@storeUser')->name('user.store');

Route::post('users/{id}', 'HomeController@updateUserRole')->name('users.update.role')->middleware('role:admin');
Route::post('update-staff', 'HomeController@customStaffRegisterUpdate')->name('staff.update');
Route::post('register-staff', 'HomeController@customStaffRegisterStore')->name('staff.store');
Route::get('getUserInformation', 'HomeController@getUserInformation');
Route::post('user/delete', 'HomeController@deletUser')->name('delete.user');







Route::get('appointments', 'AppointmentController@index')->name('appointment.index');
Route::get('appointments/rm', 'AppointmentController@setRmAppointment')->name('appointment.rm.appointment');
Route::get('appointments/counselor', 'AppointmentController@setCounselorAppointment')->name('appointment.counselor.appointment');
Route::get('client/appointments/{client_id}', 'AppointmentController@clientAppointment')->name('client.appointment');






Route::get('program/delete/{program_id}', 'ProgramController@deleteProgram')->name('delete.program');
Route::get('findClientProgram', 'ProgramController@clientProgram');
Route::get('findProgramStep', 'ProgramController@programStep');
Route::get('findClientProgramStep', 'ProgramController@clientProgramStep');
Route::get('getIndividualClientProgram', 'ProgramController@individualClientProgram');

Route::post('program/edit', 'ProgramController@editProgram')->name('edit.program');






Route::get('target/department', 'TargetController@department')->name('target.department');
Route::get('target/rm', 'TargetController@rm')->name('target.rm');
Route::get('target/counselor', 'TargetController@counselor')->name('target.counselor');
Route::get('set/target/{user_id}', 'TargetController@setTarget')->name('set.target');
Route::post('target/{user_id}', 'TargetController@storeTarget')->name('store.target');
Route::post('target/department/set', 'TargetController@storeDepartmentTarget')->name('target.department.store');







Route::get('comment/task/{client_task_id}', 'CommentController@task')->name('comment.tasks');
Route::post('comment/task/{client_task_id}', 'CommentController@taskCommentStore')->name('comment.tasks.store');

Route::get('comment/user/task/{user_task_id}', 'CommentController@userTask')->name('comment.user.tasks');
Route::post('comment/user/task/{user_task_id}', 'CommentController@userTaskCommentStore')->name('comment.user.tasks.store');

Route::get('comment/spouse/task/{spouse_task_id}', 'CommentController@spouseTask')->name('comment.spouse.tasks');
Route::post('comment/spouse/task/{spouse_task_id}', 'CommentController@spouseTaskCommentStore')->name('comment.spouse.tasks.store');

Route::get('comment/appointment/{client_appointment_id}', 'CommentController@appointment')->name('comment.appointments');
Route::post('comment/appointment/{client_appointment_id}', 'CommentController@appointmentCommentStore')->name('comment.appointment.store');







Route::get('sms/{client_id}', 'TextController@smsIndex')->name('sms.index');
Route::get('email/{client_id}', 'TextController@emailIndex')->name('email.index');

Route::post('email/{client_id}', 'TextController@sendEmail')->name('email.send');
Route::post('sms/{client_id}', 'TextController@sendSms')->name('sms.send');




Route::get('form', 'FormController@index')->name('home.form');
Route::post('form', 'FormController@store')->name('home.form.store');




Route::get('history/payment', 'PaymentController@paymentHistory')->name('payment.history');
Route::get('payment/verification/{payment}', 'PaymentController@verification')->name('payment.verification');
Route::get('payment/disapprove/{payment}', 'PaymentController@disapprove')->name('payment.disapprove');

Route::get('payment/cheque/verification/{payment_type}/{status}', 'PaymentController@chequeVerification')->name('payment.cheque.verification');
Route::get('payment/online/verification/{payment_type}/{status}', 'PaymentController@onlineVerification')->name('payment.online.verification');
Route::get('payment/generate-invoice/{payment}', 'PaymentController@generateInvoice')->name('payment.generate.invoice');

Route::get('payment/statement/account', 'PaymentController@statement')->name('payment.statement');
Route::get('payment/show/statement/{payment_id}', 'PaymentController@showStatement')->name('payment.show.statement');

Route::get('payment/incomes/expenses/approve/{income_expenes_id}/{approve}', 'PaymentController@recheck')->name('payment.recheck');

Route::get('payment/bank/account', 'PaymentController@bankAccount')->name('payment.bank.account');
Route::get('payment/account/detials/{account}', 'PaymentController@accountDetails')->name('payment.account.detials');
Route::post('payment/account/transfer', 'PaymentController@transfer')->name('payment.account.transfer');

Route::get('payment/create/incomes', 'PaymentController@createIncome')->name('payment.income');
Route::post('payment/store/incomes', 'PaymentController@storeIncomesAndExpenses')->name('payment.store.income.and.expenses');

Route::get('payment/create/expenses', 'PaymentController@createExpense')->name('payment.expense');

Route::get('payment/incomes/expenses', 'PaymentController@showIncomesAndExpenses')->name('payment.show.income.and.expenses');

Route::post('payment/update/incomes/expenses', 'PaymentController@updateIncomesAndExpenses')->name('payment.update.income.and.expenses');
Route::get('payment/delete/incomes/expenses/{income_and_expenses_id}', 'PaymentController@deleteIncomeAndExpenses')->name('payment.delete.income.and.expenses');
Route::get('payment/clear/due/{payment}', 'PaymentController@clearDue')->name('payment.clear.due');

Route::post('payment/types', 'PaymentController@types')->name('payment.types');
Route::get('findIncomeAndExpenses', 'PaymentController@findIncomeAndExpenses');
Route::get('payment/acknowledgement/thank-you', function() {
	return view('payments.acknowledgement');
})->name('payment.acknowledgement');
Route::get('payment/structure/client/{payment_type_id}/{type}', 'PaymentController@structureClient')->name('payment.structure.client');

Route::get('payment/income/pdf', 'PaymentController@generateIncomePDF')->name('payment.income.pdf');
Route::get('payment/expense/pdf', 'PaymentController@generateExpensePDF')->name('payment.expense.pdf');
Route::get('payment/income/expense/pdf', 'PaymentController@generateIncomeExpensePDF')->name('payment.income.expense.pdf');


Route::get('payment/client/refund', 'PaymentController@clientRefund')->name('payment.client.refund');
Route::get('payment/client/refund/history', 'PaymentController@clientRefundHistory')->name('payment.client.refund.history');
Route::get('payment/client/refund/delete/{payment_id}', 'PaymentController@clientRefundDelete')->name('payment.client.refund.delete');


Route::post('payment/store/client/refund', 'PaymentController@storeClientRefund')->name('payment.store.client.refund');
Route::get('payment/client/dues', 'PaymentController@clientDues')->name('payment.client.dues');
Route::get('payment/client/dues/detials/{payment_id}', 'PaymentController@clientDuesDetails')->name('payment.client.dues.details');
Route::get('payment/client/dues/payment/{payment_id}', 'PaymentController@duePayment')->name('payment.client.dues.payment');
Route::post('payment/client/dues/payment/store', 'PaymentController@storeDuePayment')->name('payment.client.dues.payment.store');
Route::get('payment/client/dues/history', 'PaymentController@dueHistory')->name('payment.client.dues.history');
Route::get('payment/client/dues/pdf/{payment_id}', 'PaymentController@generateDuePDF')->name('payment.client.dues.pdf');
Route::get('payment/client/payment/recheck/{payment_id}', 'PaymentController@recheckPayment')->name('payment.client.payment.recheck');
Route::get('payment/client/unverified/cheque', 'PaymentController@unverifiedCheques')->name('payment.client.unverified.cheques');
Route::get('payment/client/online/payments', 'PaymentController@onlinePayments')->name('payment.client.online.payments');
Route::get('payment/client/recheck/{payment_type_id}', 'PaymentController@recheckPaymentType')->name('payment.client.recheck.payment_type');
Route::get('payment/client/recheck/types/list', 'PaymentController@recheckPaymentTypeList')->name('payment.client.recheck.types.list');

Route::get('payment/client/edit/types/{payment_type_id}', 'PaymentController@editPaymentType')->name('payment.client.edit.types.list');
Route::post('payment/client/update/types/', 'PaymentController@updatePaymentType')->name('payment.client.update.type');
Route::post('payment/client/delete/reissue/', 'PaymentController@deleteAndReissue')->name('payment.client.delete.and.reissue');

Route::post('payment/delete/payment', 'PaymentController@deletePayment')->name('payment.delete');

Route::post('payment/update/cheque-info', 'PaymentController@updateChequeInfo')->name('payment.update.cheque.info');
Route::post('payment/update/online-info', 'PaymentController@updateOnlineInfo')->name('payment.update.online.info');

Route::get('payment/notes/{client_id}', 'PaymentController@paymentNotes')->name('payment.notes');
Route::post('payment/store/notes', 'PaymentController@storePaymentNotes')->name('payment.store.notes');
Route::post('payment/delete/note', 'PaymentController@deletePaymentNote')->name('payment.delete.note');
Route::post('payment/edit/note', 'PaymentController@editPaymentNote')->name('payment.edit.note');

Route::get('getClientPaymentId', 'PaymentController@getClientPaymentId');
Route::get('getChequeInfo', 'PaymentController@getChequeInfo');
Route::get('getOnlineInfo', 'PaymentController@getOnlineInfo');
Route::get('findNoteInfo', 'PaymentController@findNoteInfo');








Route::get('invoice/opening/{client_id}', 'InvoiceController@opening')->name('invoice.opening');



Route::get('myclients/{user_id}', 'UserController@myclients')->name('user.clients');



Route::get('step/delete/{step_id}', 'StepController@delete')->name('step.delete');



Route::get('/', function () {
    return view('welcome');
})->name('main');



Route::get('thank-you', function () {
    	return view('file.acknowledgement');
})->name('thanks');


Route::get('reports', 'ReportController@index')->name('reports.index');
Route::get('reports/profit/loss', 'ReportController@profitAndLoss')->name('reports.profit.loss');
Route::post('reports/monthly', 'ReportController@monthly')->name('reports.monthly');
Route::post('reports/our-current-clients', 'ReportController@ourCurrentClients')->name('reports.our_current_clients');




Route::resource('gcalendar', 'gCalendarController');
Route::get('appointment/{client_id}', 'gCalendarController@setAppointment')->name('appointment.client');
Route::get('oauth', 'gCalendarController@oauth')->name('oauthCallback');
Route::get('email/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendEmail')->name('email');
Route::get('sms/{rm_id}/{client_id}/{appointment_id}', 'gCalendarController@sendSMS')->name('sms');