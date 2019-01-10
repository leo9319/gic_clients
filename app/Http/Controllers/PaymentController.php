<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Program;
use App\RmClient;
use App\CounsellorClient;
use App\Target;
use App\ClientFileInfo;
use App\Step;
use App\ClientProgram;
use App\Task;
use App\ClientTask;
use App\SpouseTask;
use App\PaymentType;
use App\PaymentNote;
use App\IncomeExpense;
use Carbon\Carbon;
use Redirect;
use Auth;
use PDF;
use URL;
use DB;
use Mail;


class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct() 
    {
        $this->middleware('auth');
        $this->middleware('role:admin,accountant')->only('bankAccount', 'accountDetails', 'recheck', 'clientRefund');
    }


    public function index()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['clients'] = User::userRole('client')->where('status', 'active')->get();
        $data['programs'] = Program::all();

        return view('payments.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function types(Request $request)
    {
        $validatedData = $request->validate([
            'program_id' => 'required',
            'step_id' => 'required',
            'opening_fee' => 'required_without_all:embassy_student_fee,service_solicitor_fee,other',
            'embassy_student_fee' => 'required_without_all:opening_fee,service_solicitor_fee,other',
            'service_solicitor_fee' => 'required_without_all:opening_fee,embassy_student_fee,other',
            'other' => 'required_without_all:opening_fee,embassy_student_fee,service_solicitor_fee',
        ]);

        $data['previous'] = URL::to('/');
        $data['active_class'] = 'payments';
        $data['date'] = Carbon::parse($request->date)->toDateTimeString();

        $receipt_id = 'GIC-' . $request->client_id . $request->program_id . $request->step_id;

        $payment = Payment::updateOrCreate(
            [
                'client_id' => $request->client_id,
                'program_id' => $request->program_id,
                'step_id' => $request->step_id
            ],
            [
                'receipt_id' => $receipt_id,
                'location' => $request->location,
                'opening_fee' => $request->opening_fee,
                'embassy_student_fee' => $request->embassy_student_fee,
                'service_solicitor_fee' => $request->service_solicitor_fee,
                'other' => $request->other,
                'created_by' => Auth::user()->id,
                'comments' => $request->comments,
                'created_at' => $data['date'],
            ]);

        $data['total_amount'] = $request->opening_fee + $request->embassy_student_fee + $request->service_solicitor_fee + $request->other;

        $data['payment_id'] = $payment->id;

        // Check if the client has made payment for this program and step before:

        $previous_payments =  PaymentType::where('payment_id', $data['payment_id'])->get();

        if(count($previous_payments) > 0) {
            $data['message'] = 'Payment has been made previously on this program and step';
        } 

        return view('payments.types', $data);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $counter = 0;
        $counter = $request->counter;
        $payment_id = Input::get('payment_id');

        for ($i=0; $i < $counter + 1; $i++) { 
            $charge = 0;
            $cheque_verified = 1;
            $online_verified = 1;
            $payment_type = Input::get('payment_type-' . $i);
            $bank_deposited = Input::get('bank_name-' . $i);
            $after_charge = 0;

            if($payment_type == 'card') {

                $POS_machine = Input::get('pos_machine-' . $i);
                $city_bank = Input::get('city_bank-' . $i);
                $card_type = Input::get('card_type-' . $i);
                $charge = 0;
                $bank_deposited = Input::get('bank_name-' . $i);

                switch ($POS_machine) {

                    case 'city':

                        $bank_deposited = 'scb';
                        if($card_type == 'amex') {
                            $charge = 2.5;
                        } else {
                            $charge = ($city_bank == 'yes') ? 1 : 2;
                        }
                        
                        break;

                    case 'brac':

                        $bank_deposited = 'brac';
                        $charge = 1.4;
                        break;

                    case 'ebl':
                        
                        $bank_deposited = 'ebl';
                        $charge = 1.5;
                        break;

                    case 'ucb':
                        
                        $bank_deposited = 'ucb';
                        $charge = 1.5;
                        break;

                    case 'dbbl':
                        
                        $bank_deposited = 'dbbl';
                        $charge = 1.5;
                        break;

                    default:
                    // Do nothing
                }

            } else if($payment_type == 'cheque') {

                $cheque_verified = -1;

            } else if($payment_type == 'online') {

                $online_verified = -1;

            } else if($payment_type == 'bkash_corporate' || $payment_type == 'upay') {

                $charge = 1.5;

            } else if($payment_type == 'bkash_salman') {

                $charge = 2;

            } else {

                // Do nothing
            }

            $amount_paid = Input::get('total_amount-' . $i);

            if($charge > 0) {
                $after_charge = $amount_paid - (($charge / 100) * $amount_paid);
            } else {
                $after_charge = $amount_paid;
            }

            PaymentType::create([
                'payment_id' => $payment_id,
                'payment_type' => $payment_type,
                'card_type' => Input::get('card_type-' . $i),
                'name_on_card' => Input::get('name_on_card-' . $i),
                'card_number' => Input::get('card_number-' . $i),
                'expiry_date' => Input::get('expiry_date-' . $i),
                'pos_machine' => Input::get('pos_machine-' . $i),
                'approval_code' => Input::get('approval_code-' . $i),
                'phone_number' => Input::get('phone_number-' . $i),
                'cheque_number' => Input::get('cheque_number-' . $i),
                'bank_name' => $bank_deposited,
                'cheque_verified' => $cheque_verified,
                'online_verified' => $online_verified,
                'bank_charge' => $charge,
                'amount_paid' => Input::get('total_amount-' . $i),
                'amount_received' => $after_charge,
                'deposit_date' => Input::get('deposit_date-' . $i),
                'created_at' => $request->date,
            ]);
            
        }

        // If there is a due date

        if($request->due_date) {
            // there is a due date field

            $total_paid = PaymentType::where('payment_id', $payment_id)->sum('amount_paid');

            $dues = $request->total_amount - $total_paid;

            Payment::find($payment_id)->update([
                'dues' => $dues,
                'due_date' => Input::get('due_date'),
            ]);
        } 

        $payment = Payment::find(Input::get('payment_id'));
        $program_id = $payment->program_id;
        $client_id = $payment->client_id;
        $step_id = (integer)$payment->step_id;

        // Get the current steps of the client
        $client_programs = ClientProgram::assignedSteps($program_id, $client_id);

        if($client_programs->steps) {
            $step_array = json_decode($client_programs->steps);
        } else {
            $step_array = [];
        }

        // Add the steps in the steps array
        if (!(in_array($step_id, $step_array))) {
            array_push($step_array, $step_id);
        }        

        // If the step is a new one then it is added to client profile
        ClientProgram::updateOrCreate(
            ['client_id' => $client_id, 'program_id' => $program_id],
            ['steps' => json_encode($step_array)]
        );

        // Getting the list of tasks for the client
        $program_tasks = Task::getUserTasks($step_id, 'client');

        // Getting the list of tasks for the spouse
        $spouse_program_tasks = Task::getUserTasks($step_id, 'spouse');

        // Adding all the tasks to the client task table
        foreach ($program_tasks as $program_task) {
            ClientTask::updateOrCreate(
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $program_task->id],
                ['client_id' => $client_id,
                 'step_id' => $step_id,
                 'task_id' => $program_task->id,
                 'deadline' => Carbon::now()->addDays($program_task->duration),
                ]
            );
        }

        // Adding all the tasks to the spouse task table
        foreach ($spouse_program_tasks as $spouse_program_task) {
            SpouseTask::updateOrCreate(
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $spouse_program_task->id],
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $spouse_program_task->id]
            );
        }

        // Getting the current list of RMs currently assigned to the client
        $associated_rms = RmClient::where('client_id', $client_id)->pluck('rm_id')->toArray();

        // Getting the current list of Counselor currently assigned to the client
        $associated_counselors = CounsellorClient::where('client_id', $client_id)->pluck('counsellor_id')->toArray();

        // adding one to the target for the assigned RM
        foreach ($associated_rms as $associated_rm) {
            Target::addOneToTarget($associated_rm);
        }

        // adding one to the target for the assigned Counslor
        foreach ($associated_counselors as $associated_counselor) {
            Target::addOneToTarget($associated_counselor);
        }

        return redirect()->route('payment.acknowledgement');        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        // Get the client information:

        $data['client'] = User::find($payment->client_id);
        $data['client_file_info'] = ClientFileInfo::where('client_id', $payment->client_id)->first();
        $data['program'] = Program::find($payment->program_id);
        $data['payment'] = $payment;
        $data['step'] = Step::find($payment->step_id);

        $data['total_amount'] = $payment->opening_fee + $payment->embassy_student_fee + $payment->service_solicitor_fee + $payment->other;

        $data['payment_types'] = PaymentType::where('payment_id', $payment->id)
                                 ->where('cheque_verified', '!=', 0)
                                 ->where('online_verified', '!=', 0)
                                 ->where('refund_payment', '!=', 1)
                                 ->get();

        return view('payments.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['payment'] = $payment;


        $data['programs'] = DB::table('client_programs AS CP')
                            ->join('programs AS P', 'P.id', 'CP.program_id')
                            ->where('CP.client_id', $payment->client_id)->get();

        $data['steps'] = Step::getProgramAllStep($payment->program_id);

        $data['payment_types'] = PaymentType::where('payment_id', $payment->id)
                                  ->where('due_payment', 0)
                                  ->where('refund_payment', 0)
                                  ->get();

        return view('payments.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $validatedData = $request->validate([
            'opening_fee' => 'required_without_all:embassy_student_fee,service_solicitor_fee,other',
            'embassy_student_fee' => 'required_without_all:opening_fee,service_solicitor_fee,other',
            'service_solicitor_fee' => 'required_without_all:opening_fee,embassy_student_fee,other',
            'other' => 'required_without_all:opening_fee,embassy_student_fee,service_solicitor_fee',
        ]);

        $total_amount = $request->opening_fee + $request->embassy_student_fee + $request->service_solicitor_fee + $request->other;

        $total_amount_paid = array_sum(array_column($request->payment, 'amount_paid'));

        $due = $total_amount - $total_amount_paid;

        $date = Carbon::parse($request->date)->toDateTimeString();

        Payment::find($payment->id)->update([
            'location' => $request->location,
            'opening_fee' => $request->opening_fee,
            'embassy_student_fee' => $request->embassy_student_fee,
            'service_solicitor_fee' => $request->service_solicitor_fee,
            'other' => $request->other,
            'dues' => $due,
            'comments' => $request->comments,
            'created_at' => $date,
        ]);


        PaymentType::where('payment_id', $payment->id)->delete();

        foreach ($request->payment as $key => $value) {

            $payment_type =  isset($value['payment_type']) ? $value['payment_type'] : NULL;
            $amount_paid =  isset($value['amount_paid']) ? $value['amount_paid'] : NULL;
            $bank_name =  isset($value['bank_name']) ? $value['bank_name'] : NULL;
            $bank_charge =  isset($value['bank_charge']) ? $value['bank_charge'] : NULL;
            $name_on_card =  isset($value['name_on_card']) ? $value['name_on_card'] : NULL;
            $card_number =  isset($value['card_number']) ? $value['card_number'] : NULL;
            $expiry_date =  isset($value['expiry_date']) ? $value['expiry_date'] : NULL;
            $pos_machine =  isset($value['pos_machine']) ? $value['pos_machine'] : NULL;
            $approval_code =  isset($value['approval_code']) ? $value['approval_code'] : NULL;
            $cheque_number =  isset($value['cheque_number']) ? $value['cheque_number'] : NULL;
            $cheque_verified =  isset($value['cheque_verified']) ? $value['cheque_verified'] : 1;
            $bank_name =  isset($value['bank_name']) ? $value['bank_name'] : NULL;
            $bank_charge =  isset($value['bank_charge']) ? $value['bank_charge'] : NULL;
            $phone_number =  isset($value['phone_number']) ? $value['phone_number'] : NULL;
            $deposit_date =  isset($value['deposit_date']) ? $value['deposit_date'] : NULL;
            $card_type =  isset($value['card_type']) ? $value['card_type'] : NULL;

            $after_charge = $amount_paid - (($bank_charge / 100) * $amount_paid);

            PaymentType::create([

                'payment_id' => $payment->id,
                'payment_type' => $payment_type,
                'card_type' => $card_type,
                'name_on_card' => $name_on_card,
                'card_number' => $card_number,
                'expiry_date' => $expiry_date,
                'pos_machine' => $pos_machine,
                'approval_code' => $approval_code,
                'phone_number' => $phone_number,
                'cheque_number' => $cheque_number,
                'bank_name' => $bank_name,
                'cheque_verified' => $cheque_verified,
                'bank_charge' => $bank_charge,
                'amount_paid' => $amount_paid,
                'amount_received' => $after_charge,
                'deposit_date' => $deposit_date,

            ]);
        }

        return redirect()->back()->with('success', 'Payment has been edited successfully');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        Payment::find($payment->id)->delete();
        PaymentType::where('payment_id', $payment->id)->delete();

        return redirect()->back();
    }

    public function deletePayment(Request $request)
    {
        PaymentType::where('payment_id', $request->payment_id)->delete();
        Payment::find($request->payment_id)->delete();

        return redirect()->back();
    }

    public function paymentHistory()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $user_role = Auth::user()->user_role;

        if($user_role == 'rm') {

            $rm_id = Auth::user()->id;
            $client_ids = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['payments'] = Payment::whereIn('client_id', $client_ids)->orderBy('created_at', 'desc')->get();

        } else if($user_role == 'counselor'){

            $counselor_id = Auth::user()->id;
            $client_ids = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['payments'] = Payment::whereIn('client_id', $client_ids)->orderBy('created_at', 'desc')->get();

        } else {

            $data['payments'] = Payment::orderBy('created_at', 'desc')->get();
        }

        if($user_role == 'accountant') {
            return view('payments.accountant.history', $data);
        }

        return view('payments.history', $data);
    }

    public function verification($payment_id)
    {
        Payment::find($payment_id)->update(['recheck' => 0]);

        return redirect()->back();
    }

    public function chequeVerification(PaymentType $payment_type, $status)
    {
        PaymentType::find($payment_type->id)->update(['cheque_verified' => $status]);

        // if the cheque is dissapproved then the due will increase
        if(!$status) {
            $payment_id = $payment_type->payment_id;
            $total_amount = $this->findingTotalAmount($payment_id);
            $total_amount_paid = $this->findingTotalPaidAmount($payment_id);
            $dues = $total_amount - $total_amount_paid;

            Payment::find($payment_id)->update(['dues' => $dues]);
        }

        return redirect()->back();
    }

    public function onlineVerification(PaymentType $payment_type, $status)
    {
        PaymentType::find($payment_type->id)->update(['online_verified' => $status]);

        // if the online payment is dissapproved then the due will increase
        if(!$status) {
            $payment_id = $payment_type->payment_id;
            $total_amount = $this->findingTotalAmount($payment_id);
            $total_amount_paid = $this->findingTotalPaidAmount($payment_id);
            $dues = $total_amount - $total_amount_paid;

            Payment::find($payment_id)->update(['dues' => $dues]);
        }

        return redirect()->back();
    }

    public function generateInvoice(Payment $payment)
    {
        $client = User::find($payment->client_id);
        $client_additional_info = ClientFileInfo::where('client_id', $payment->client_id)->first();

        $data['name'] = $client->name;
        $data['address'] = $client_additional_info->address;
        $data['country_of_choice'] = json_decode($client_additional_info->country_of_choice);
        $data['mobile'] = $client->mobile;
        $data['email'] = $client->email;
        $data['date'] = Carbon::parse($payment->created_at)->format('d/m/Y');
        $data['client_code'] = $client->client_code;
        $data['program'] = Program::find($payment->program_id)->program_name;
        $data['step'] = Step::find($payment->step_id);
        $data['receipt_id'] = $payment->receipt_id;
        $data['opening_fee'] = $payment->opening_fee;
        $data['embassy_student_fee'] = $payment->embassy_student_fee;
        $data['service_solicitor_fee'] = $payment->service_solicitor_fee;
        $data['other'] = $payment->other;
        // $data['amount_paid'] = PaymentType::where('payment_id', $payment->id)->sum('amount_paid');
        $data['dues'] = $payment->dues;
        $data['due_date'] = $payment->due_date;
        $data['comments'] = $payment->comments;

        $data['payment_methods'] = PaymentType::where('payment_id', $payment->id)
                                    ->where('cheque_verified', '!=', 0)
                                    ->where('online_verified', '!=', 0)
                                    ->where('refund_payment', '!=', 1)
                                    ->get();

        $created_by = User::find($payment->created_by);
        $data['created_by'] = $created_by ? $created_by->name : '';

        $pdf = PDF::loadView('invoice.index', $data);
        return $pdf->download($client->client_code.'-payment-invoice.pdf');
    }


    public function statement()
    {
        $data['active_class'] = 'payments';
        $data['previous'] = URL::to('/dashboard');

        // Get the assigend client for the RMS:

        if(Auth::user()->user_role == 'counselor') {

            $counselor_id = Auth::user()->id;
            $client_ids = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['clients'] = User::find($client_ids);

        } else if(Auth::user()->user_role == 'rm') {
            
            $rm_id = Auth::user()->id;
            $client_ids = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['clients'] = User::find($client_ids);

        } else {
            $data['clients'] = User::userRole('client')->where('status', 'active')->get();
        }

        return view('payments.statement', $data);

    }

    public function showStatement($client_id)
    {
        $data['client'] = User::find($client_id);
        $data['rms'] = RmClient::getAssignedRms($client_id);
        $data['counselors'] = CounsellorClient::assignedCounselor($client_id);

        $data['payment_histories'] = Payment::where('client_id', $client_id)->get();

        $data['payable'] =  $data['payment_histories']->sum('opening_fee') +
                            $data['payment_histories']->sum('embassy_student_fee') +
                            $data['payment_histories']->sum('service_solicitor_fee') +
                            $data['payment_histories']->sum('other');

        // Get all the payment_ids of that client:

        $payment_ids = $data['payment_histories']->pluck('id');
        $data['refunds'] = PaymentType::whereIn('payment_id', $payment_ids)->where('refund_payment', 1)->get();

        // $data['amount_refunded'] = $income_expenes->sum('total_amount');
        $payment_ids = Payment::where('client_id', $client_id)->pluck('id');
        $data['payment_methods'] = $payment_type = PaymentType::whereIn('payment_id', $payment_ids);
        $data['paid'] = $payment_type->sum('amount_paid');
        $data['received'] = $payment_type->sum('amount_received');
        $data['dues'] = $data['payment_histories']->sum('dues');

        return view('payments.show_statement', $data);
    }

    public function recheck($income_expenes_id, $status)
    {
        IncomeExpense::find($income_expenes_id)->update(['recheck' => $status]);

        return redirect()->back();
    }

    public function recheckPayment($payment_id)
    {
        Payment::find($payment_id)->update(['recheck' => 1]);

        return redirect()->back();
    }

    public function recheckPaymentType($payment_type_id)
    {
        PaymentType::find($payment_type_id)->update(['recheck' => 1]);

        $accountant_email = User::where('user_role', 'accountant')->first()->email;

        $data = [
            'id' => $payment_type_id,
            'email' => $accountant_email,
            'subject' => 'Recheck Payment'
        ];

        Mail::send('mail.recheck', $data, function($message) use ($data) {
            $message->from('s.simab@gmail.com', 'Recheck Payment');
            $message->to($data['email']);
            $message->subject($data['subject']);
        });

        return redirect()->back();
        
    }

    public function recheckPaymentTypeList()
    {
        $data['active_class'] = 'payments';
        $data['payments_types'] = PaymentType::where('recheck', 1)->get();

        return view('payments.recheck_payment_lists', $data);
    }

    public function editPaymentType($payment_type_id)
    {
        $data['active_class'] = 'payments';
        $data['payment_type'] = PaymentType::find($payment_type_id);

        return view('payments.edit_payment_type', $data);
    }

    public function deleteAndReissue(Request $request)
    {

        $charge = 0;
        $cheque_verified = 1;
        $online_verified = 1;
        $after_charge = 0;
        $id = Input::get('id');
        $payment_id = Input::get('payment_id');
        $payment_type = Input::get('payment_type');
        $bank_deposited = Input::get('bank_name');
      

        if($payment_type == 'card') {

            $POS_machine = Input::get('pos_machine_mod');
            $city_bank = Input::get('city_bank');
            $card_type = Input::get('card_type');


            switch ($POS_machine) {

                case 'city':

                    $bank_deposited = 'scb';
                    if($card_type == 'amex') {
                        $charge = 2.5;
                    } else {
                        $charge = ($city_bank == 'yes') ? 1 : 2;
                    }
                    
                    break;

                case 'brac':

                    $bank_deposited = 'brac';
                    $charge = 1.4;
                    break;

                case 'ebl':
                    
                    $bank_deposited = 'ebl';
                    $charge = 1.5;
                    break;

                case 'ucb':
                    
                    $bank_deposited = 'ucb';
                    $charge = 1.5;
                    break;

                case 'dbbl':
                    
                    $bank_deposited = 'dbbl';
                    $charge = 1.5;
                    break;

                default:
                // Do nothing
            }

        } else if($payment_type == 'cheque') {

            $cheque_verified = -1;

        } else if($payment_type == 'online') {

            $online_verified = -1;

        } else if($payment_type == 'bkash_corporate' || $payment_type == 'upay') {

            $charge = 1.5;

        } else if($payment_type == 'bkash_salman') {

            $charge = 2;

        } else {

            // Do nothing
        }

        $amount_paid = Input::get('total_amount');

        if($charge > 0) {
            $after_charge = $amount_paid - (($charge / 100) * $amount_paid);
        } else {
            $after_charge = $amount_paid;
        }  

        PaymentType::find($id)->delete();
 
        PaymentType::create([
            'payment_id' => $payment_id,
            'payment_type' => $payment_type,
            'card_type' => Input::get('card_type'),
            'name_on_card' => Input::get('name_on_card'),
            'card_number' => Input::get('card_number'),
            'expiry_date' => Input::get('expiry_date'),
            'pos_machine' => Input::get('pos_machine_mod'),
            'approval_code' => Input::get('approval_code'),
            'phone_number' => Input::get('phone_number'),
            'cheque_number' => Input::get('cheque_number'),
            'bank_name' => $bank_deposited,
            'cheque_verified' => $cheque_verified,
            'online_verified' => $online_verified,
            'deposit_date' => Input::get('deposit_date'),
            'due_payment' => $request->due_payment,
            'bank_charge' => $charge,
            'amount_paid' => Input::get('total_amount'),
            'amount_received' => $after_charge,
        ]);

        return redirect()->route('payment.client.recheck.types.list');


    }

    public function updatePaymentType(Request $request)
    {
        $payment_id = $request->payment_id;

        if($request->bank_charge > 0) {
            $after_charge = $request->amount_paid - (($request->bank_charge / 100) * $request->amount_paid);
        } else {
            $after_charge = $request->amount_paid;
        }

        PaymentType::find($request->id)->update([
                'payment_id' => $payment_id,
                'payment_type' => $request->payment_type,
                'card_type' => $request->card_type,
                'name_on_card' => $request->name_on_card,
                'card_number' => $request->card_number,
                'expiry_date' => $request->expiry_date,
                'pos_machine' => $request->pos_machine,
                'approval_code' => $request->approval_code,
                'phone_number' => $request->phone_number,
                'cheque_number' => $request->cheque_number,
                'deposit_date' => $request->deposit_date,
                'bank_name' => $request->bank_name,
                'amount_paid' => $request->amount_paid,
                'amount_received' => $after_charge,
                'recheck' => 0,
            ]);

        $total_amount = Payment::find($payment_id)->totalAmount();
        $amount_paid = PaymentType::where('payment_id', $payment_id)->sum('amount_paid');
        $dues = $total_amount - $amount_paid;

        Payment::find($payment_id)->update(['dues'=>$dues]);

        return redirect()->route('payment.client.recheck.types.list');

        
        // return $request->all();
    }

    public function bankAccount()
    {
        $data['active_class'] = 'payments';
        $data['previous'] = URL::to('/dashboard');
        $bank_account_income_expenses = IncomeExpense::where('recheck', 0)->get();
        $bank_account_client = PaymentType::where([
            'cheque_verified' => 1,
            'online_verified' => 1,
        ])->get();

        $banks = [
            'cash' => 0,
            'scb' => 0,
            'city' => 0,
            'dbbl' => 0,
            'ebl' => 0,
            'ucb' => 0,
            'brac' => 0,
            'agrani' => 0,
            'icb' => 0,
            'salman account' => 0,
            'kamran account' => 0
         ];

         $data['bank_accounts'] = [
            'cash' => 'CASH',
            'scb' => 'SCB',
            'city' => 'CITY',
            'dbbl' => 'DBBL',
            'ebl' => 'EBL',
            'ucb' => 'UCB',
            'brac' => 'BRAC',
            'agrani' => 'AGRANI',
            'icb' => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        // Calculating the amount without the refunds

        foreach ($bank_account_client->where('refund_payment', '!=', 1) as $bac_key => $bac_value) {

            foreach ($banks as $key => $value) {
                
                if($bac_value['bank_name'] == $key) {
                    $banks[$key] += $bac_value['amount_received'];
                }
            }
        }

        // Calculating payment with the refunds

        foreach ($bank_account_client->where('refund_payment', '=', 1) as $bac_key => $bac_value) {

            foreach ($banks as $key => $value) {
                
                if($bac_value['bank_name'] == $key) {
                    $banks[$key] -= $bac_value['amount_received'];
                }
            }
        }

        foreach ($bank_account_income_expenses as $baie_key => $baie_value) {

            foreach ($banks as $key => $value) {
                if($baie_value['bank_name'] == $key) {
                    $banks[$key] += $baie_value['total_amount'];
                }
            }
        }

        $data['banks'] = $banks; 

        return view('payments.bank_account', $data);
        
    }

    public function accountDetails($account)
    {
        $data['active_class'] = 'payments';
        $data['previous'] = URL::to('payment/bank/account');
        $data['account'] = $account;

        $payment_histories = $data['payment_histories'] = PaymentType::where('bank_name', $account)
                             ->where('cheque_verified', 1)
                             ->where('online_verified', 1)
                             ->get();

        $incomes_and_expenses = $data['incomes_and_expenses'] = IncomeExpense::where([
            'bank_name' => $account,
            'recheck' => 0,
        ])->get();


        $payment_breakdown = array();
        $index = 0; 

        foreach ($payment_histories as $key => $value) {
            $payment_breakdowns[$index]['date'] = $value->created_at;
            $payment_breakdowns[$index]['location'] = $value->payment->location;
            $payment_breakdowns[$index]['client_name'] = (isset($value->payment->userInfo->name)) ? $value->payment->userInfo->name : 'Client Removed';
            $payment_breakdowns[$index]['type'] = (isset($value->payment->programInfo->program_name)) ? $value->payment->programInfo->program_name : 'Program Removed';
            
            if($value->refund_payment == 1) {
                $payment_breakdowns[$index]['paid'] = -$value->amount_paid;
                $payment_breakdowns[$index]['received'] = -$value->amount_received;
                $payment_breakdowns[$index]['description'] = (isset($value->payment->stepInfo->step_name)) ? $value->payment->stepInfo->step_name . '(Refund)': 'Step Removed';
            } else {
                $payment_breakdowns[$index]['paid'] = $value->amount_paid;
                $payment_breakdowns[$index]['received'] = $value->amount_received;
                $payment_breakdowns[$index]['description'] = (isset($value->payment->stepInfo->step_name)) ? $value->payment->stepInfo->step_name : 'Step Removed';
            }
            $payment_breakdowns[$index]['bank_charge'] = $value->bank_charge;
            
            $index++;
        }

        foreach ($incomes_and_expenses as $key => $value) {
            $payment_breakdowns[$index]['date'] = $value->created_at;
            $payment_breakdowns[$index]['location'] = $value->location;
            $payment_breakdowns[$index]['client_name'] = '';
            $payment_breakdowns[$index]['type'] = $value->payment_type;
            $payment_breakdowns[$index]['description'] = $value->description;
            $payment_breakdowns[$index]['paid'] = $value->total_amount;
            $payment_breakdowns[$index]['bank_charge'] = 0;
            $payment_breakdowns[$index]['received'] = $value->total_amount;
            $index++;
        }

        $this->array_sort_by_column($payment_breakdowns, 'date');

        $data['payment_breakdowns'] = $payment_breakdowns;

        $data['total_amount'] = $data['payment_histories']->where('refund_payment', '!=', 1)->sum('amount_received') - $data['payment_histories']->where('refund_payment', '=', 1)->sum('amount_received') + $data['incomes_and_expenses']->sum('total_amount');

        return view('payments.account_details', $data);
    }

    public function transfer(Request $request)
    {
        $date_timestamp = Carbon::parse($request->date)->toDateTimeString();

        IncomeExpense::create([
            'payment_type' => 'Cash Transfer In',
            'bank_name' => $request->to_account,
            'total_amount' => $request->amount,
            'recheck' => 0,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'created_at' => $date_timestamp, 
        ]);

        IncomeExpense::create([
            'payment_type' => 'Cash Transfer Out',
            'bank_name' => $request->from_account,
            'total_amount' => -1 * $request->amount,
            'recheck' => 0,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'created_at' => $date_timestamp, 
        ]);

        return redirect()->back();

    }

    public function createIncome()
    {
        $data['active_class'] = 'payment';
        $data['previous'] = URL::to('/dashboard');

        $data['bank_accounts'] = [
            'cash' => 'Cash',
            'scb' => 'SCB',
            'city' => 'City Bank',
            'dbbl' => 'DBBL',
            'ebl' => 'EBL',
            'ucb' => 'UCB',
            'brac' => 'BRAC',
            'agrani' => 'Agrani Bank',
            'icb' => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        return view('payments.create_income', $data);
    }

    public function storeIncomesAndExpenses(Request $request)
    {
        $date_timestamp = Carbon::parse($request->date)->toDateTimeString();

        $amount = $request->amount;

        if($request->type == 'expense') {
            $amount = -($amount);
        }

        IncomeExpense::create([
            'payment_type' => $request->type,
            'total_amount' => $amount,
            'bank_name' => $request->bank_name,
            'location' => $request->location,
            'recheck' => 1,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'created_at' => $date_timestamp
        ]);

        return redirect()->back()->with('success', 'Entry created successfully!');

    }

    public function createExpense()
    {
        $data['active_class'] = 'payment';
        $data['previous'] = URL::to('/dashboard');
        
        $data['bank_accounts'] = [
            'cash' => 'Cash',
            'scb' => 'SCB',
            'city' => 'City Bank',
            'dbbl' => 'DBBL',
            'ebl' => 'EBL',
            'ucb' => 'UCB',
            'brac' => 'BRAC',
            'agrani' => 'Agrani Bank',
            'icb' => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        return view('payments.create_expense', $data);
    }

    public function showIncomesAndExpenses() 
    {
        $data['active_class'] = 'payment';
        $data['previous'] = URL::to('/dashboard');

        $data['transactions'] = IncomeExpense::orderBy('recheck', 'DESC')->get();

        $data['bank_accounts'] = [
            'cash' => 'Cash',
            'scb' => 'SCB',
            'city' => 'City Bank',
            'dbbl' => 'DBBL',
            'ebl' => 'EBL',
            'ucb' => 'UCB',
            'brac' => 'BRAC',
            'agrani' => 'Agrani Bank',
            'icb' => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        return view('payments.show_income_and_expenses', $data);
    }

    public function updateIncomesAndExpenses(Request $request)
    {
        $date_timestamp = Carbon::parse($request->date)->toDateTimeString();

        $amount = $request->amount;

        if($request->type == 'expense') {
            $amount = -($amount);
        }

        IncomeExpense::find($request->payment_id)->update([
            'bank_name' => $request->bank_name,
            'location' => $request->location,
            'total_amount' => $amount,
            'recheck' => 1,
            'description' => $request->description,
            'created_at' => $date_timestamp,
        ]);

        return redirect()->back();
    }

    public function deleteIncomeAndExpenses(Request $request)
    {
        IncomeExpense::find($request->id)->delete();

        return redirect()->back();
    }

    public function findIncomeAndExpenses(Request $request)
    {
        $data = IncomeExpense::where('id', $request->id)->first();

        return response()->json($data);
    }

    public function clearDue(Payment $payment)
    {
        $after_charge = $payment->total_amount - (($payment->bank_charges / 100) * $payment->total_amount);

        Payment::find($payment->id)->update([
            'amount_paid' => $payment->total_amount,
            'total_after_charge' => $after_charge,
        ]);

        return redirect()->back();
    }

    public function structureClient($payment_type_id, $type)
    {
        $data['payment_info'] = PaymentType::find($payment_type_id);

        switch ($type) {
            case 'card':
                return view('payments.types.card', $data);
                break;

            case 'cash':
                return view('payments.types.cash_online', $data);
                break;

            case 'cheque':
                return view('payments.types.cheque', $data);
                break;

            case 'online':
                return view('payments.types.cash_online', $data);
                break;

            case 'bkash_salman':
                return view('payments.types.bkash_upay', $data);
                break;

            case 'bkash_corporate':
                return view('payments.types.bkash_upay', $data);
                break;

            case 'upay':
                return view('payments.types.bkash_upay', $data);
                break;
            
            default:
                # code...
                break;
        }
    }

    public function generateIncomePDF() 
    {
        $data['incomes'] = IncomeExpense::where('payment_type', 'income')->get();

        return view('payments.income_pdf', $data);
    }

    public function generateExpensePDF() 
    {
        $data['expenses'] = IncomeExpense::where('payment_type', 'expense')->get();

        return view('payments.expenses_pdf', $data);
    }

    public function generateIncomeExpensePDF() 
    {
        $data['incomes'] = IncomeExpense::where('payment_type', 'income')->get();
        $data['expenses'] = IncomeExpense::where('payment_type', 'expense')->get();

        return view('payments.income_expense_pdf', $data);
    }

    public function clientRefund()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['clients'] = User::userRole('client')->where('status', 'active')->get();
        $data['programs'] = Program::all();

        $data['bank_accounts'] = [
            'cash' => 'CASH',
            'scb' => 'SCB',
            'city' => 'CITY',
            'dbbl' => 'DBBL',
            'ebl' => 'EBL',
            'ucb' => 'UCB',
            'brac' => 'BRAC',
            'agrani' => 'AGRANI',
            'icb' => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        return view('payments.refund', $data);
    }

    public function storeClientRefund(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required|not_in:0',
            'program_id' => 'required|not_in:0',
            'payment_type' => 'required|not_in:0',
            'bank_name' => 'required',
            'amount' => 'required|min:1',
        ]);

        PaymentType::create([
            'payment_id' => $request->payment_id,
            'payment_type' => $request->payment_type,
            'bank_name' => $request->bank_name,
            'cheque_number' => $request->cheque_number,
            'amount_paid' => $request->amount,
            'amount_received' => $request->amount,
            'refund_payment' => 1,

        ]);

        return redirect()->back()->with('success', 'Refund has been created!');

    }

    public function clientRefundHistory()
    {
        $data['active_class'] = 'dues';

        if(Auth::user()->user_role == 'counselor') {

            $counselor_id = Auth::user()->id;
            $client_ids = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $payment_ids = Payment::whereIn('client_id', $client_ids)->pluck('id');
            $data['refunds'] = PaymentType::whereIn('payment_id', $payment_ids)->where('refund_payment', 1)->get();

        } else if(Auth::user()->user_role == 'rm') {
            
            $rm_id = Auth::user()->id;
            $client_ids = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $payment_ids = Payment::whereIn('client_id', $client_ids)->pluck('id');
            $data['refunds'] = PaymentType::whereIn('payment_id', $payment_ids)->where('refund_payment', 1)->get();

        } else {
            $data['refunds'] = PaymentType::where('refund_payment', 1)->get();
        }

        return view('payments.refund_history', $data);
    }

    public function clientRefundDelete($payment_id)
    {
        PaymentType::find($payment_id)->delete();
        
        return redirect()->back();
    }

    public function clientDues()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';

        if(Auth::user()->user_role == 'counselor') {

            $counselor_id = Auth::user()->id;
            $client_ids = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['all_dues'] = Payment::whereIn('client_id', $client_ids)->where('dues', '>', 0)->get();

        } else if(Auth::user()->user_role == 'rm') {
            
            $rm_id = Auth::user()->id;
            $client_ids = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['all_dues'] = Payment::whereIn('client_id', $client_ids)->where('dues', '>', 0)->get();

        } else {
            $data['all_dues'] = Payment::where('dues', '>', 0)->get();
        }

        
        return view('payments.dues', $data);
    }

    public function clientDuesDetails(Payment $payment_id)
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['payment'] = $payment_id;

        $data['program_fee'] = $payment_id->opening_fee + $payment_id->embassy_student_fee + $payment_id->service_solicitor_fee + $payment_id->other;

        $data['payment_types'] = PaymentType::where('payment_id', $payment_id->id)
                                 ->where('cheque_verified', '!=', 0)
                                 ->where('online_verified', '!=', 0)
                                 ->where('refund_payment', '!=', 1)
                                 ->get();

        return view('payments.due_details', $data);
    }

    public function duePayment($payment_id)
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['payment_id'] = $payment_id;
        $data['total_amount'] = Payment::find($payment_id)->dues;

        return view('payments.due_payment', $data);
    }

    public function paymentNotes($client_id)
    {
        $data['active_class'] = 'payments';
        $data['client'] = User::find($client_id);
        $data['notes'] = PaymentNote::where('client_id', $client_id)->get();

        return view('payments.payment_notes', $data);
    }

    public function storePaymentNotes(Request $request)
    {
        PaymentNote::create($request->all());

        return redirect()->back();
    }

    public function deletePaymentNote(Request $request)
    {
        PaymentNote::find($request->note_id)->delete();

        return redirect()->back();
    }

    public function editPaymentNote(Request $request)
    {
        PaymentNote::find($request->note_id)->update([
            'date' => $request->date,
            'description' => $request->description,
            'amount' => $request->amount
        ]);

        return redirect()->back();
    }

    public function storeDuePayment(Request $request)
    {
        $counter = 0;
        $counter = $request->counter;
        $payment_id = Input::get('payment_id');

        for ($i=0; $i < $counter + 1; $i++) {
            $charge = 0;
            $cheque_verified = 1;
            $online_verified = 1;
            $payment_type = Input::get('payment_type-' . $i);
            $bank_deposited = Input::get('bank_name-' . $i);
            $after_charge = 0;

            if($payment_type == 'card') {

                $POS_machine = Input::get('pos_machine-' . $i);
                $city_bank = Input::get('city_bank-' . $i);
                $card_type = Input::get('card_type-' . $i);
                $charge = 0;
                $bank_deposited = Input::get('bank_name-' . $i);

                switch ($POS_machine) {

                    case 'city':

                        $bank_deposited = 'scb';
                        if($card_type == 'amex') {
                            $charge = 2.5;
                        } else {
                            $charge = ($city_bank == 'yes') ? 1 : 2;
                        }
                        
                        break;

                    case 'brac':

                        $bank_deposited = 'brac';
                        $charge = 1.4;
                        break;

                    case 'ebl':
                        
                        $bank_deposited = 'ebl';
                        $charge = 1.5;
                        break;

                    case 'ucb':
                        
                        $bank_deposited = 'ucb';
                        $charge = 1.5;
                        break;

                    case 'dbbl':
                        
                        $bank_deposited = 'dbbl';
                        $charge = 1.5;
                        break;

                    default:
                    // Do nothing
                }

            } else if($payment_type == 'cheque') {

                $cheque_verified = -1;

            } else if($payment_type == 'online') {

                $online_verified = -1;

            } else if($payment_type == 'bkash_corporate') {

                $charge = 1.5;

            } else if($payment_type == 'bkash_salman') {

                $charge = 2;

            } else {

                // Do nothing
            }

            $amount_paid = Input::get('total_amount-' . $i);

            if($charge > 0) {
                $after_charge = $amount_paid - (($charge / 100) * $amount_paid);
            } else {
                $after_charge = $amount_paid;
            }

            PaymentType::create([
                'payment_id' => $payment_id,
                'payment_type' => $payment_type,
                'card_type' => Input::get('card_type-' . $i),
                'name_on_card' => Input::get('name_on_card-' . $i),
                'card_number' => Input::get('card_number-' . $i),
                'expiry_date' => Input::get('expiry_date-' . $i),
                'pos_machine' => Input::get('pos_machine-' . $i),
                'approval_code' => Input::get('approval_code-' . $i),
                'phone_number' => Input::get('phone_number-' . $i),
                'cheque_number' => Input::get('cheque_number-' . $i),
                'bank_name' => $bank_deposited,
                'cheque_verified' => $cheque_verified,
                'online_verified' => $online_verified,
                'due_payment' => 1,
                'bank_charge' => $charge,
                'amount_paid' => Input::get('total_amount-' . $i),
                'amount_received' => $after_charge,
            ]);
            
        }

        $total_amount_paid = Input::get('amount_paid_active');
        $current_due = Payment::find($payment_id)->dues;

        $updated_due = $current_due - $total_amount_paid;

        Payment::find($payment_id)->update([
                'dues' => $updated_due,
                'due_cleared_date' => Carbon::now(),
            ]);

        $payment = Payment::find($payment_id);

        $client = User::find($payment->client_id);
        $client_additional_info = ClientFileInfo::where('client_id', $payment->client_id)->first();

        $due['name'] = $client->name;
        $due['address'] = $client_additional_info->address;
        $due['country_of_choice'] = json_decode($client_additional_info->country_of_choice);
        $due['mobile'] = $client->mobile;
        $due['email'] = $client->email;
        $due['date'] = Carbon::now()->format('d-m-Y');
        $due['client_code'] = $client->client_code;
        $due['program'] = Program::find($payment->program_id)->program_name;
        $due['step'] = Step::find($payment->step_id);
        $due['opening_fee'] = $payment->opening_fee;
        $due['embassy_student_fee'] = $payment->embassy_student_fee;
        $due['service_solicitor_fee'] = $payment->service_solicitor_fee;
        $due['other'] = $payment->other;
        $due['amount_paid'] = PaymentType::where('payment_id', $payment->id)->sum('amount_paid');
        $due['payments'] = PaymentType::where('payment_id', $payment_id)->first();


        $created_by = User::find($payment->created_by);
        $due['created_by'] = $created_by ? $created_by->name : '';

        // $pdf = PDF::loadView('invoice.due', $due);
        // return $pdf->download($client->client_code.'-due-clearance-invoice.pdf');

        return redirect()->route('payment.acknowledgement');  

    }

    public function dueHistory()
    {
        $data['active_class'] = 'dues';
        $due_payment_type_ids = PaymentType::select('payment_id')->groupBy('payment_id')->where('due_payment', 1)->pluck('payment_id');

        if(Auth::user()->user_role == 'counselor') {

            $counselor_id = Auth::user()->id;
            $client_ids = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');

            $data['due_payments'] = Payment::whereIn('client_id', $client_ids)->orwhereIn('id', $due_payment_type_ids)->get();

        } else if(Auth::user()->user_role == 'rm') {
            
            $rm_id = Auth::user()->id;
            $client_ids = RmClient::where('rm_id', $rm_id)->pluck('client_id');

            $data['due_payments'] = Payment::whereIn('client_id', $client_ids)->orwhereIn('id', $due_payment_type_ids)->get();


        } else {
            $data['due_payments'] = Payment::whereIn('id', $due_payment_type_ids)->get();
        }

        $data['due_payments'] = Payment::whereIn('id', $due_payment_type_ids)->get();

        return view('payments.due_payment_history', $data);
    }

    public function unverifiedCheques()
    {
        $data['active_class'] = 'payments';
        $data['unverified_cheques'] = PaymentType::where('payment_type', 'cheque')->where('cheque_verified', '!=', '1')->get();

        return view('payments.unverified_cheques', $data);
    }

    public function onlinePayments()
    {
        $data['active_class'] = 'payments';
        $data['online_payments'] = PaymentType::where('payment_type', 'online')->where('online_verified', '!=', '1')->get();

        return view('payments.online_payments', $data);
    }

    public function generateDuePDF($payment_id)
    {
        $due['payment'] = $payment = Payment::find($payment_id);

        $client = User::findOrFail($payment->client_id);
        $client_additional_info = ClientFileInfo::where('client_id', $payment->client_id)->first();

        $due['name'] = $client->name;
        $due['address'] = $client_additional_info->address;
        $due['country_of_choice'] = json_decode($client_additional_info->country_of_choice);
        $due['mobile'] = $client->mobile;
        $due['email'] = $client->email;
        $due['date'] = Carbon::now()->format('d-m-Y');
        $due['client_code'] = $client->client_code;
        $due['program'] = Program::find($payment->program_id)->program_name;
        $due['step'] = Step::find($payment->step_id);
        $due['opening_fee'] = $payment->opening_fee;
        $due['embassy_student_fee'] = $payment->embassy_student_fee;
        $due['service_solicitor_fee'] = $payment->service_solicitor_fee;
        $due['other'] = $payment->other;
        $due['comments'] = $payment->comments;

        // finding the previously paid amount:

        $due['payments'] = PaymentType::where('payment_id', $payment_id)
                            ->where('cheque_verified', '!=', 0)
                            ->where('online_verified', '!=', 0)
                            ->where('refund_payment', '!=', 1)
                            ->get();

        $created_by = User::find($payment->created_by);
        $due['created_by'] = $created_by ? $created_by->name : '';

        $pdf = PDF::loadView('invoice.due', $due);
        return $pdf->download('due_clearance_invoice.pdf');
    }

    public function getClientPaymentId(Request $request)
    {
        $payment = Payment::where([
                            'client_id' => $request->client_id,
                            'program_id' => $request->program_id,
                            'step_id' => $request->step_id,]
                        )->first();

        $amount_paid_without_refunds = PaymentType::where(
            'payment_id', '=', $payment->id)
        ->where('refund_payment', '!=', 1)
        ->where('cheque_verified', '=', 1)
        ->where('online_verified', '=', 1)
        ->sum('amount_paid');

        $refunds = PaymentType::where(
            'payment_id', '=', $payment->id)
        ->where('refund_payment', '=', 1)
        ->sum('amount_paid');

        $data['amount_paid'] = $amount_paid_without_refunds - $refunds;

        $data['payment_id'] = $payment->id;

        return $data;
    }

    public function updateChequeInfo(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'cheque_number' => $request->cheque_number,
            'bank_name' => $request->bank_name,
            'deposit_date' => $request->deposit_date,
        ]);

        return redirect()->back();
    }

    public function updateOnlineInfo(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'bank_name' => $request->bank_name,
            'deposit_date' => $request->deposit_date,
        ]);

        return redirect()->back();
    }

    public function getChequeInfo(Request $request)
    {
        $data = PaymentType::find($request->payment_id);

        return response()->json($data);
    }

    public function getOnlineInfo(Request $request)
    {
        $data = PaymentType::find($request->payment_id);

        return response()->json($data);
    }

    public function findNoteInfo(Request $request)
    {
        $data = PaymentNote::find($request->note_id);

        return response()->json($data);
    }

    public function findingTotalAmount($payment_id)
    {
        $payment = Payment::find($payment_id);

        return $total_amount = $payment->opening_fee + $payment->embassy_student_fee + $payment->service_solicitor_fee + $payment->other;
    }

    public function findingTotalPaidAmount($payment_id)
    {
        $payment_types = PaymentType::where('payment_id', $payment_id)
                          ->where('cheque_verified', '!=', 0)
                          ->where('online_verified', '!=', 0)
                          ->get();

        return $payment_types->sum('amount_paid');
    }

    public function findingTotalPaidAmountForOnline($payment_id)
    {
        $payment_types = PaymentType::where('payment_id', $payment_id)
                          ->where('cheque_verified', '!=', 0)
                          ->where('online_verified', '!=', 0)
                          ->get();

        return $payment_types->sum('amount_paid');
    }

    public function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
        $reference_array = array();

        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }

}
