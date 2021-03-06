<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
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
use App\BankCharge;
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
        $this->middleware('role:admin')->only('edit', 'destroy', 'bankCharges');
        $this->middleware('role:admin,accountant')->only('index', 'bankAccount', 'accountDetails', 'recheck', 'clientRefund', 'showAdvanceIncomes', 'showAdvanceExpenses', 'createIncome', 'createExpense', 'showIncomesAndExpenses', 'recheckPaymentTypeList');

    }


    public function index()
    {
        $data['previous']     = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['clients']      = User::userRole('client')->where('status', 'active')->get();
        $data['programs']     = Program::all();
        
        $data['locations'] = [
            'dhaka'      => 'Dhaka', 
            'chittagong' => 'Chittagong',
            'cox_bazar'  => 'Cox\'s bazar',
            'sylhet'     => 'Sylhet',
            'khulna'     => 'Khulna',
            'comilla'    => 'Comilla',
            'noakhali'   => 'Noakhali',
            'rajshahi'   => 'Rajshahi',
            'bagura'   => 'Bagura',
         ];

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

    public function showBankCharges()
    {
        return response(BankCharge::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function bankCharges()
    {
        $data['active_class'] = 'payments';
        $data['bank_charges'] = BankCharge::all();

        return view('payments.admin.bank_charges', $data);
    }

    public function editBankCharges(Request $request, $id)
    {
        BankCharge::find($id)->update([
            'bank_charge' => $request->bank_charge
        ]);

        return response(null, Response::HTTP_OK);
    }

    public function types(Request $request)
    {
        $validatedData = $request->validate([

            'program_id'            => 'required',
            'step_id'               => 'required',
            'opening_fee'           => 'required_without_all:embassy_student_fee,service_solicitor_fee,other',
            'embassy_student_fee'   => 'required_without_all:opening_fee,service_solicitor_fee,other',
            'service_solicitor_fee' => 'required_without_all:opening_fee,embassy_student_fee,other',
            'other'                 => 'required_without_all:opening_fee,embassy_student_fee,service_solicitor_fee',

        ]);

        $previous_payment = Payment::where([

            'client_id'  => $request->client_id,
            'program_id' => $request->program_id,
            'step_id'    => $request->step_id,

        ])->count();

        if($previous_payment > 0) {
            return redirect()->back()->with('warning', 'PAYMENT WAS RECEIVED FOR THIS STEP FROM THIS CLIENT!');
        }

        $data['active_class'] = 'payments';
        $date                 = Carbon::parse($request->date)->toDateTimeString();
        $receipt_id           = 'GIC-' . $request->client_id . $request->program_id . $request->step_id;
        $total_amount         = $request->opening_fee + 
                                $request->embassy_student_fee + 
                                $request->service_solicitor_fee + 
                                $request->other;

        $data['total_amount'] = $total_amount;

        $opening_fee           = $request->opening_fee ?? 0;
        $embassy_student_fee   = $request->embassy_student_fee ?? 0;
        $service_solicitor_fee = $request->service_solicitor_fee ?? 0;
        $other                 = $request->other ?? 0;

        $request->session()->put('receipt_id', $receipt_id);
        $request->session()->put('location', $request->location);
        $request->session()->put('client_id', $request->client_id);
        $request->session()->put('program_id', $request->program_id);
        $request->session()->put('step_id', $request->step_id);
        $request->session()->put('opening_fee', $opening_fee);
        $request->session()->put('embassy_student_fee', $embassy_student_fee);
        $request->session()->put('service_solicitor_fee', $service_solicitor_fee);
        $request->session()->put('other', $other);
        $request->session()->put('comments', $request->comments);
        $request->session()->put('created_at', $date);
        $request->session()->put('total_amount', $date);

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
        $client_id             = $request->session()->get('client_id');
        $program_id            = $request->session()->get('program_id');
        $step_id               = $request->session()->get('step_id');
        $receipt_id            = $request->session()->get('receipt_id');
        $location              = $request->session()->get('location');
        $opening_fee           = $request->session()->get('opening_fee');
        $embassy_student_fee   = $request->session()->get('embassy_student_fee');
        $service_solicitor_fee = $request->session()->get('service_solicitor_fee');
        $other                 = $request->session()->get('other');
        $comments              = $request->session()->get('comments');
        $created_at            = $request->session()->get('created_at');


        $payment = Payment::updateOrCreate(
            [
                'client_id'             => $client_id,
                'program_id'            => $program_id,
                'step_id'               => $step_id
            ],
            [
                'receipt_id'            => $receipt_id,
                'location'              => $location,
                'opening_fee'           => $opening_fee,
                'embassy_student_fee'   => $embassy_student_fee,
                'service_solicitor_fee' => $service_solicitor_fee,
                'other'                 => $other,
                'created_by'            => Auth::user()->id,
                'comments'              => $comments,
                'created_at'            => $created_at,
            ]);


        $counter = 0;
        $counter = $request->counter;
        $payment_id = $payment->id;
        $bank_charges = BankCharge::all();

        for ($i=0; $i < $counter + 1; $i++) { 
            $charge                   = 0;
            $cheque_verified          = 1;
            $online_verified          = 1;
            $bkash_salman_verified    = 1;
            $bkash_corporate_verified = 1;
            $payment_type             = Input::get('payment_type-' . $i);
            $bank_deposited           = Input::get('bank_name-' . $i);
            $after_charge             = 0;

            if($payment_type == 'card') {

                $POS_machine          = Input::get('pos_machine-' . $i);
                $city_bank            = Input::get('city_bank-' . $i);
                $card_type            = Input::get('card_type-' . $i);
                $charge               = 0;
                $bank_deposited       = Input::get('bank_name-' . $i);

                switch ($POS_machine) {

                    case 'city':

                        $bank_deposited = 'scb';
                        if($card_type == 'amex') {
                            $charge = $bank_charges->where('bank_name', 'city_bank_amex')->first()->bank_charge;
                        } else {

                            if($city_bank == 'yes') {
                                $charge = $bank_charges->where('bank_name', 'city_bank')->first()->bank_charge;
                            } else {
                                $charge = $bank_charges->where('bank_name', 'city_bank_other')->first()->bank_charge;
                            }
                        }
                        
                        break;

                    case 'brac':

                        $bank_deposited = 'brac';
                        $charge = $bank_charges->where('bank_name', 'brac_bank')->first()->bank_charge;
                        break;

                    case 'ebl':
                        
                        $bank_deposited = 'ebl';
                        $charge = $bank_charges->where('bank_name', 'ebl')->first()->bank_charge;
                        break;

                    case 'ucb':
                        
                        $bank_deposited = 'ucb';
                        $charge = $bank_charges->where('bank_name', 'ucb')->first()->bank_charge;
                        break;

                    case 'dbbl':
                        
                        $bank_deposited = 'dbbl';
                        $charge = $bank_charges->where('bank_name', 'dbbl')->first()->bank_charge;
                        break;

                    default:
                }

            } else if($payment_type == 'cheque') {

                $cheque_verified = -1;

            } else if($payment_type == 'online') {

                $online_verified = -1;

            } else if($payment_type == 'upay') {

                $charge = $bank_charges->where('bank_name', 'upay')->first()->bank_charge;

            } else if($payment_type == 'bkash_corporate') {

                $charge = $bank_charges->where('bank_name', 'bkash_corporate')->first()->bank_charge;
                $bkash_corporate_verified = -1;

            } else if($payment_type == 'bkash_salman') {

                $charge = $bank_charges->where('bank_name', 'bkash_salman')->first()->bank_charge;
                $bkash_salman_verified = -1;

            } else if($payment_type == 'pay_gic') {

                $charge = $bank_charges->where('bank_name', 'pay_gic')->first()->bank_charge;
                $online_verified = -1;

            } else if($payment_type == 'pay_gic_ssl') {

                $charge = $bank_charges->where('bank_name', 'pay_gic_ssl')->first()->bank_charge;
                $online_verified = -1;

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
                'payment_id'               => $payment_id,
                'payment_type'             => $payment_type,
                'card_type'                => Input::get('card_type-' . $i),
                'name_on_card'             => Input::get('name_on_card-' . $i),
                'card_number'              => Input::get('card_number-' . $i),
                'expiry_date'              => Input::get('expiry_date-' . $i),
                'pos_machine'              => Input::get('pos_machine-' . $i),
                'approval_code'            => Input::get('approval_code-' . $i),
                'phone_number'             => Input::get('phone_number-' . $i),
                'cheque_number'            => Input::get('cheque_number-' . $i),
                'bank_name'                => $bank_deposited,
                'cheque_verified'          => $cheque_verified,
                'online_verified'          => $online_verified,
                'bkash_salman_verified'    => $bkash_salman_verified,
                'bkash_corporate_verified' => $bkash_corporate_verified,
                'bank_charge'              => $charge,
                'amount_paid'              => Input::get('total_amount-' . $i),
                'amount_received'          => $after_charge,
                'deposit_date'             => Input::get('deposit_date-' . $i),
                'created_at'               => $created_at,
            ]);
            
        }

        // If there is a due date

        if($request->due_date) {

            $total_paid = PaymentType::where('payment_id', $payment->id)->sum('amount_paid');
            $dues       = $request->total_amount - $total_paid;

            Payment::find($payment_id)->update([
                'dues'     => $dues,
                'due_date' => Input::get('due_date'),
            ]);
        }

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
            ['steps'     => json_encode($step_array)]
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
                 'step_id'   => $step_id,
                 'task_id'   => $program_task->id,
                 'deadline'  => Carbon::now()->addDays($program_task->duration),
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

        $request->session()->forget([
            'receipt_id',
            'location',
            'client_id',
            'program_id',
            'step_id',
            'opening_fee',
            'embassy_student_fee',
            'service_solicitor_fee',
            'other',
            'comments',
            'created_at',
            'total_amount'
        ]);

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
        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {

            return view('payments.admin.show', compact('payment'));

        } elseif($user_role == 'accountant') {

            return view('payments.accountant.show', compact('payment'));

        }

        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        $data['previous']     = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['payment']      = $payment;


        $data['programs'] = DB::table('client_programs AS CP')
                            ->join('programs AS P', 'P.id', 'CP.program_id')
                            ->where('CP.client_id', $payment->client_id)->get();

        $data['steps']    = Step::getProgramAllStep($payment->program_id);

        $data['payment_types'] = PaymentType::where('payment_id', $payment->id)
                                  ->where('cheque_verified', '!=', 0)
                                  ->where('online_verified', '!=', 0)
                                  ->where('bkash_salman_verified', '!=', 0)
                                  ->where('bkash_corporate_verified', '!=', 0)
                                  ->where('refund_payment', '!=', 1)
                                  ->get();

        $data['locations'] = [
            'dhaka'      => 'Dhaka', 
            'chittagong' => 'Chittagong',
            'cox_bazar'  => 'Cox\'s bazar',
            'sylhet'     => 'Sylhet',
            'khulna'     => 'Khulna',
            'comilla'    => 'Comilla',
            'noakhali'   => 'Noakhali',
            'rajshahi'   => 'Rajshahi',
            'bagura'   => 'Bagura',
         ];

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
            'opening_fee'           => 'required_without_all:embassy_student_fee,service_solicitor_fee,other',
            'embassy_student_fee'   => 'required_without_all:opening_fee,service_solicitor_fee,other',
            'service_solicitor_fee' => 'required_without_all:opening_fee,embassy_student_fee,other',
            'other'                 => 'required_without_all:opening_fee,embassy_student_fee,service_solicitor_fee',
        ]);

        $total_amount = $request->opening_fee + 
                        $request->embassy_student_fee + 
                        $request->service_solicitor_fee + 
                        $request->other;

        $total_amount_paid = array_sum(array_column($request->payment, 'amount_paid'));
        $due               = $total_amount - $total_amount_paid;
        $date              = Carbon::parse($request->date)->toDateTimeString();

        Payment::find($payment->id)->update([
            'location'              => $request->location,
            'opening_fee'           => $request->opening_fee,
            'embassy_student_fee'   => $request->embassy_student_fee,
            'service_solicitor_fee' => $request->service_solicitor_fee,
            'other'                 => $request->other,
            'dues'                  => $due,
            'comments'              => $request->comments,
            'created_at'            => $date,
        ]);


        PaymentType::where('payment_id', $payment->id)->delete();

        foreach ($request->payment as $key => $value) {

            $payment_type             = isset($value['payment_type']) ? $value['payment_type'] : NULL;
            $amount_paid              = isset($value['amount_paid']) ? $value['amount_paid'] : NULL;
            $bank_name                = isset($value['bank_name']) ? $value['bank_name'] : NULL;
            $bank_charge              = isset($value['bank_charge']) ? $value['bank_charge'] : NULL;
            $name_on_card             = isset($value['name_on_card']) ? $value['name_on_card'] : NULL;
            $card_number              = isset($value['card_number']) ? $value['card_number'] : NULL;
            $expiry_date              = isset($value['expiry_date']) ? $value['expiry_date'] : NULL;
            $pos_machine              = isset($value['pos_machine']) ? $value['pos_machine'] : NULL;
            $approval_code            = isset($value['approval_code']) ? $value['approval_code'] : NULL;
            $cheque_number            = isset($value['cheque_number']) ? $value['cheque_number'] : NULL;
            $cheque_verified          = isset($value['cheque_verified']) ? $value['cheque_verified'] : 1;
            $online_verified          = isset($value['online_verified']) ? $value['online_verified'] : 1;
            $bkash_salman_verified    = isset($value['bkash_salman_verified']) ? $value['bkash_salman_verified'] : 1;
            $bkash_corporate_verified = isset($value['bkash_corporate_verified']) ? $value['bkash_corporate_verified'] : 1;
            $bank_name                = isset($value['bank_name']) ? $value['bank_name'] : NULL;
            $bank_charge              = isset($value['bank_charge']) ? $value['bank_charge'] : NULL;
            $phone_number             = isset($value['phone_number']) ? $value['phone_number'] : NULL;
            $deposit_date             = isset($value['deposit_date']) ? $value['deposit_date'] : NULL;
            $card_type                = isset($value['card_type']) ? $value['card_type'] : NULL;
            $created_at               = isset($value['created_at']) ? $value['created_at'] : NULL;

            $after_charge = $amount_paid - (($bank_charge / 100) * $amount_paid);

            PaymentType::create([

                'payment_id'               => $payment->id,
                'payment_type'             => $payment_type,
                'card_type'                => $card_type,
                'name_on_card'             => $name_on_card,
                'card_number'              => $card_number,
                'expiry_date'              => $expiry_date,
                'pos_machine'              => $pos_machine,
                'approval_code'            => $approval_code,
                'phone_number'             => $phone_number,
                'cheque_number'            => $cheque_number,
                'bank_name'                => $bank_name,
                'online_verified'          => $online_verified,
                'cheque_verified'          => $cheque_verified,
                'bkash_salman_verified'    => $bkash_salman_verified,
                'bkash_corporate_verified' => $bkash_corporate_verified,
                'bank_charge'              => $bank_charge,
                'amount_paid'              => $amount_paid,
                'amount_received'          => $after_charge,
                'deposit_date'             => $deposit_date,
                'created_at'               => $created_at,

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

    public function paymentHistory($period = 'all')
    {
        $data['active_class'] = 'payments';
        $user_role            = Auth::user()->user_role;

        if($user_role == 'admin' || $user_role == 'accountant') {

            if($period == 60) {

                $from = Carbon::now()->subDays(60);
                $to   = Carbon::now();

                $data['payments'] = Payment::orderBy('created_at', 'desc')
                                        ->whereBetween('created_at', [$from, $to])
                                        ->get();
            } else {
                $data['payments'] = Payment::orderBy('created_at', 'desc')->get();
            }

            if($user_role == 'accountant') {

                return view('payments.accountant.history', $data);

            } elseif($user_role == 'admin') {

                return view('payments.admin.history', $data);

            }

        } else if($user_role == 'counselor'){

            $counselor_id     = Auth::user()->id;
            $client_ids       = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['payments'] = Payment::whereIn('client_id', $client_ids)->orderBy('created_at', 'desc')->get();

            return view('payments.history', $data);

        } else if($user_role == 'rm') {

            $rm_id            = Auth::user()->id;
            $client_ids       = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['payments'] = Payment::whereIn('client_id', $client_ids)->orderBy('created_at', 'desc')->get();

            return view('payments.history', $data);

        } else {

            //
        }
        
    }

    public function paymentHistoryBeta()
    {
        $data['active_class'] = 'payments';
        $user_role            = Auth::user()->user_role;

        if($user_role == 'rm') {

            $rm_id            = Auth::user()->id;
            $client_ids       = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['payments'] = Payment::whereIn('client_id', $client_ids)->orderBy('created_at', 'desc')->get();

            return view('payments.history', $data);

        } else if($user_role == 'counselor'){

            $counselor_id     = Auth::user()->id;
            $client_ids       = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['payments'] = Payment::whereIn('client_id', $client_ids)->orderBy('created_at', 'desc')->get();

            return view('payments.history', $data);

        } else if($user_role == 'accountant' || $user_role == 'operation') {

            return view('payments.accountant.history_beta', $data);

        } else if($user_role == 'admin') {

            return view('payments.admin.history_beta', $data);

        }

        return view('payments.history_beta', $data);
    }

    public function paymentHistoryData()
    {
        if(request()->ajax())
        {

            $start_date = (!empty($_GET["start_date"])) ? ($_GET["start_date"]) : '';
            $end_date   = (!empty($_GET["end_date"])) ? ($_GET["end_date"]) : ''; 

            $payments   = Payment::query();

            if($start_date && $end_date){

                $start_date = date('Y-m-d', strtotime($start_date));
                $end_date   = date('Y-m-d', strtotime($end_date));

                $payments->whereDate('P.created_at', '>=', $start_date)->whereDate('P.created_at', '<=', $end_date);

            }

            $payments   = $payments->from('payments AS P')
                                ->leftJoin('users AS U', 'U.id', '=', 'P.client_id')
                                ->leftJoin('programs AS PG', 'PG.id', '=', 'P.program_id')
                                ->leftJoin('steps AS S', 'S.id', '=', 'P.step_id')
                                ->select(
                                    'P.id', 
                                    'P.created_at AS payment_date', 
                                    'P.location',
                                    'P.comments', 
                                    'P.client_id', 
                                    'U.client_code', 
                                    'U.name', 
                                    'PG.program_name', 
                                    'S.step_name', 
                                     DB::raw(
                                        "P.opening_fee +
                                         P.embassy_student_fee +
                                         P.service_solicitor_fee +
                                         P.other AS invoice_amount"
                                ))
                                ->get();

        foreach ($payments as $payment) {

            // for calculating the amount_paid
            $amount_paid = DB::table('payment_types')
                                ->where('payment_id', $payment->id)
                                ->where('cheque_verified', 1)
                                ->where('online_verified', 1)
                                ->where('bkash_salman_verified', 1)
                                ->where('bkash_corporate_verified', 1)
                                ->where('refund_payment', '!=', 1)
                                ->groupBy('payment_id')
                                ->sum('amount_paid');

            // for finding the assigned Counselor
            $counselor_clients = CounsellorClient::assignedCounselor($payment->client_id);
            $rm_clients        = RmClient::getAssignedRms($payment->client_id);

            $counselors        = [];
            $rms               = [];

            foreach ($counselor_clients as $counselor_client) {
                $counselors[] = $counselor_client->user->name ?? 'N/A';
            }

            foreach ($rm_clients as $rm_client) {
                $rms[] = $rm_client->user->name ?? 'N/A';
            }

            $payment->counselors  = $counselors;
            $payment->rms         = $rms;
            $payment->amount_paid = $amount_paid;

        }


            return datatables()->of($payments)
                    ->addColumn('comments', function($data){

                        $button = $data->comments.' '.'<i id="'.$data->id.'" class="fa fa-edit" onclick="editNote(this)"></i></td> ';

                        return $button;
                    })
                    ->addColumn('action', function($data){

                        $button = '<a href="'.url('payment/generate-invoice/'.$data->id).'" class="btn btn-info btn-sm button2">Generate Invoice</a>';

                        return $button;
                    })
                    ->addColumn('view_details', function($data){

                        $button = '<a href="'.url('payment/'.$data->id).'" class="btn btn-secondary btn-sm button2">View Payment</a>';

                        return $button;
                    })
                    ->addColumn('edit', function($data){

                        $button = '<a href="'.url('payment/'.$data->id.'/edit').'" class="btn btn-primary btn-sm button2">Edit</a>';

                        return $button;
                    })
                    ->addColumn('delete', function($data){

                        $button = '<button type="button" name="edit" id="'.$data->id.'" class="delete btn btn-danger btn-sm">Delete</button>';

                        return $button;
                    })
                    ->rawColumns(['comments', 'action', 'view_details', 'edit', 'delete'])
                    ->make(true);
        }
        return view('ajax_index');
    }

    public function verification($payment_id)
    {
        Payment::find($payment_id)->update(['recheck' => 0]);

        return redirect()->back();
    }

    public function chequeApproved(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'cheque_verified' => 1,
            'created_at'      => $request->date_despisted,
            'deposit_date'    => $request->date_despisted,
        ]);

        return redirect()->back();
    }

    public function chequeDissapproved(PaymentType $payment_type)
    {
        PaymentType::find($payment_type->id)->update(['cheque_verified' => 0]);

        $payment_id        = $payment_type->payment_id;
        $total_amount      = $this->findingTotalAmount($payment_id);
        $total_amount_paid = $this->findingTotalPaidAmount($payment_id);
        $dues              = $total_amount - $total_amount_paid;

        Payment::find($payment_id)->update(['dues' => $dues]);

        return redirect()->back();
    }

    public function onlineApproved(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'online_verified' => 1,
            'created_at'      => $request->date_desposted,
            'deposit_date'    => $request->date_desposted,
        ]);

        return redirect()->back();
    }

    public function onlineDissapproved(PaymentType $payment_type)
    {
        PaymentType::find($payment_type->id)->update(['online_verified' => 0]);

        $payment_id        = $payment_type->payment_id;
        $total_amount      = $this->findingTotalAmount($payment_id);
        $total_amount_paid = $this->findingTotalPaidAmount($payment_id);
        $dues              = $total_amount - $total_amount_paid;

        Payment::find($payment_id)->update(['dues' => $dues]);

        return redirect()->back();
    }

    public function bkashSalmanApproved(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'bkash_salman_verified' => 1,
            'created_at'            => $request->date_deposited,
            'deposit_date'          => $request->date_deposited,
        ]);

        return redirect()->back();

        // return $request->all();
    }

    public function bkashCorporateApproved(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'bkash_corporate_verified' => 1,
            'created_at'               => $request->date_deposited,
            'deposit_date'             => $request->date_deposited,
        ]);

        return redirect()->back();
    }

    public function bkashSalmanDissapproved(PaymentType $payment_type)
    {
        PaymentType::find($payment_type->id)->update(['bkash_salman_verified' => 0]);

        $payment_id        = $payment_type->payment_id;
        $total_amount      = $this->findingTotalAmount($payment_id);
        $total_amount_paid = $this->findingTotalPaidAmount($payment_id);
        $dues              = $total_amount - $total_amount_paid;

        Payment::find($payment_id)->update(['dues' => $dues]);

        return redirect()->back();
    }

    public function bkashCorporateDissapproved(PaymentType $payment_type)
    {
        PaymentType::find($payment_type->id)->update(['bkash_corporate_verified' => 0]);

        $payment_id        = $payment_type->payment_id;
        $total_amount      = $this->findingTotalAmount($payment_id);
        $total_amount_paid = $this->findingTotalPaidAmount($payment_id);
        $dues              = $total_amount - $total_amount_paid;

        Payment::find($payment_id)->update(['dues' => $dues]);

        return redirect()->back();
    }

    public function generateInvoice(Payment $payment)
    {
        $client                        = User::find($payment->client_id);
        $client_additional_info        = ClientFileInfo::where('client_id', $payment->client_id)->first();

        $data['name']                  = $client->name;
        $data['address']               = $client_additional_info->address;
        $data['country_of_choice']     = json_decode($client_additional_info->country_of_choice);
        $data['mobile']                = $client->mobile;
        $data['email']                 = $client->email;
        $data['date']                  = Carbon::parse($payment->created_at)->format('d/m/Y');
        $data['client_code']           = $client->client_code;
        $data['program']               = Program::find($payment->program_id)->program_name;
        $data['step']                  = Step::find($payment->step_id);
        $data['receipt_id']            = $payment->receipt_id;
        $data['opening_fee']           = $payment->opening_fee;
        $data['embassy_student_fee']   = $payment->embassy_student_fee;
        $data['service_solicitor_fee'] = $payment->service_solicitor_fee;
        $data['other']                 = $payment->other;
        $data['due_date']              = $payment->due_date;
        $data['comments']              = $payment->comments;

        $data['payment_methods']       = PaymentType::where('payment_id', $payment->id)
                                            ->where('cheque_verified', '!=', 0)
                                            ->where('online_verified', '!=', 0)
                                            ->where('bkash_salman_verified', '!=', 0)
                                            ->where('bkash_corporate_verified', '!=', 0)
                                            ->where('refund_payment', '!=', 1)
                                            ->get();

        $created_by                    = User::find($payment->created_by);
        $data['created_by']            = $created_by ? $created_by->name : '';

        $data['dues']                  = $payment->totalAmount() - $payment->totalVerifiedPayment->sum('amount_paid');

        $pdf = PDF::loadView('invoice.index', $data);
        return $pdf->download($client->client_code.'-payment-invoice.pdf');
    }


    public function statement()
    {
        $data['active_class'] = 'payments';
        $data['previous']     = URL::to('/dashboard');

        // Get the assigend client for the RMS:

        if(Auth::user()->user_role == 'counselor') {

            $counselor_id    = Auth::user()->id;
            $client_ids      = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['clients'] = User::find($client_ids);

        } else if(Auth::user()->user_role == 'rm') {
            
            $rm_id           = Auth::user()->id;
            $client_ids      = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['clients'] = User::find($client_ids);

        } else {

            $data['clients'] = User::userRole('client')->where('status', 'active')->get();
        }

        return view('payments.statement', $data);

    }

    public function showStatement($client_id)
    {
        $data['active_class']      = 'payments';
        $data['client']            = User::find($client_id);
        $data['rms']               = RmClient::getAssignedRms($client_id);
        $data['counselors']        = CounsellorClient::assignedCounselor($client_id);
        $data['payment_histories'] = Payment::where('client_id', $client_id)->get();

        $data['payable']           =  $data['payment_histories']->sum('opening_fee') +
                                      $data['payment_histories']->sum('embassy_student_fee') +
                                      $data['payment_histories']->sum('service_solicitor_fee') +
                                      $data['payment_histories']->sum('other');

        // Get all the payment_ids of that client:

        $payment_ids               = $data['payment_histories']->pluck('id');
        $data['refunds']           = PaymentType::whereIn('payment_id', $payment_ids)->where('refund_payment', 1)->get();
        $payment_ids               = Payment::where('client_id', $client_id)->pluck('id');
        $data['payment_methods']   = $payment_type = PaymentType::whereIn('payment_id', $payment_ids);

        $data['payment_received']  = $payment_type->where('cheque_verified', 1)
                                       ->where('online_verified', 1)
                                       ->where('bkash_salman_verified', 1)
                                       ->where('bkash_corporate_verified', 1)
                                       ->where('refund_payment', 0)
                                       ->sum('amount_paid');

        $data['paid']            = $payment_type->sum('amount_paid');
        $data['received']        = $payment_type->sum('amount_received');
        $data['dues']            = $data['payment_histories']->sum('dues');

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
            'id'      => $payment_type_id,
            'email'   => $accountant_email,
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
        $data['active_class']   = 'payments';
        $data['payments_types'] = PaymentType::where('recheck', 1)->get();

        return view('payments.recheck_payment_lists', $data);
    }

    public function editPaymentType(PaymentType $payment_type)
    {
        $data['active_class'] = 'payments';
        $data['payment_type'] = $payment_type;

        return view('payments.edit_payment_type', $data);
    }

    public function deleteAndReissue(Request $request)
    {
        $charge                   = 0;
        $cheque_verified          = 1;
        $online_verified          = 1;
        $bkash_salman_verified    = 1;
        $bkash_corporate_verified = 1;
        $after_charge             = 0;
        $id                       = Input::get('id');
        $payment_id               = Input::get('payment_id');
        $payment_type             = Input::get('payment_type');
        $bank_deposited           = Input::get('bank_name');
        $date                     = Carbon::parse(Input::get('date'))->toDateTimeString();
        $bank_charges             = BankCharge::all();
      

        if($payment_type == 'card') {

            $POS_machine = Input::get('pos_machine_mod');
            $city_bank   = Input::get('city_bank');
            $card_type   = Input::get('card_type');


            switch ($POS_machine) {

                case 'city':

                    $bank_deposited = 'scb';
                    if($card_type == 'amex') {
                        $charge = $bank_charges->where('bank_name', 'city_bank_amex')->first()->bank_charge;
                    } else {
                        if($city_bank == 'yes') {
                            $charge = $bank_charges->where('bank_name', 'city_bank')->first()->bank_charge;
                        } else {
                            $charge = $bank_charges->where('bank_name', 'city_bank_other')->first()->bank_charge;
                        }
                    }
                    
                    break;

                case 'brac':

                    $bank_deposited = 'brac';
                    $charge = $bank_charges->where('bank_name', 'brac_bank')->first()->bank_charge;
                    break;

                case 'ebl':
                    
                    $bank_deposited = 'ebl';
                    $charge = $bank_charges->where('bank_name', 'ebl')->first()->bank_charge;
                    break;

                case 'ucb':
                    
                    $bank_deposited = 'ucb';
                    $charge = $bank_charges->where('bank_name', 'ucb')->first()->bank_charge;
                    break;

                case 'dbbl':
                    
                    $bank_deposited = 'dbbl';
                    $charge = $bank_charges->where('bank_name', 'dbbl')->first()->bank_charge;
                    break;

                default:
                // Do nothing
            }

        } else if($payment_type == 'cheque') {

            $cheque_verified = -1;

        } else if($payment_type == 'online') {

            $online_verified = -1;

        } else if($payment_type == 'upay') {

            $charge = $bank_charges->where('bank_name', 'upay')->first()->bank_charge;

        } else if($payment_type == 'bkash_corporate') {

            $charge = $bank_charges->where('bank_name', 'bkash_salman')->first()->bank_charge;
            $bkash_corporate_verified = -1;

        } else if($payment_type == 'bkash_salman') {

            $charge = $bank_charges->where('bank_name', 'bkash_salman')->first()->bank_charge;
            $bkash_salman_verified = -1;

        } else if($payment_type == 'pay_gic') {

            $charge = $bank_charges->where('bank_name', 'pay_gic')->first()->bank_charge;
            $online_verified = -1;

        } else if($payment_type == 'pay_gic_ssl') {

            $charge = $bank_charges->where('bank_name', 'pay_gic_ssl')->first()->bank_charge;
            $online_verified = -1;

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

            'payment_id'               => $payment_id,
            'payment_type'             => $payment_type,
            'card_type'                => Input::get('card_type'),
            'name_on_card'             => Input::get('name_on_card'),
            'card_number'              => Input::get('card_number'),
            'expiry_date'              => Input::get('expiry_date'),
            'pos_machine'              => Input::get('pos_machine_mod'),
            'approval_code'            => Input::get('approval_code'),
            'phone_number'             => Input::get('phone_number'),
            'cheque_number'            => Input::get('cheque_number'),
            'bank_name'                => $bank_deposited,
            'cheque_verified'          => $cheque_verified,
            'online_verified'          => $online_verified,
            'bkash_salman_verified'    => $bkash_salman_verified,
            'bkash_corporate_verified' => $bkash_corporate_verified,
            'deposit_date'             => Input::get('deposit_date'),
            'due_payment'              => $request->due_payment,
            'bank_charge'              => $charge,
            'amount_paid'              => Input::get('total_amount'),
            'amount_received'          => $after_charge,
            'created_at'               => $date,

        ]);

        // update the due

        $payment         = Payment::find($payment_id);
        $total_amount    = $payment->totalAmount();
        $amount_received = $payment->totalVerifiedPayment->sum('amount_paid');
        $dues            = $total_amount - $amount_received;

        $payment->update(['dues' => $dues]);

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

                'payment_id'      => $payment_id,
                'payment_type'    => $request->payment_type,
                'card_type'       => $request->card_type,
                'name_on_card'    => $request->name_on_card,
                'card_number'     => $request->card_number,
                'expiry_date'     => $request->expiry_date,
                'pos_machine'     => $request->pos_machine,
                'approval_code'   => $request->approval_code,
                'phone_number'    => $request->phone_number,
                'cheque_number'   => $request->cheque_number,
                'bank_name'       => $request->bank_name,
                'amount_paid'     => $request->amount_paid,
                'amount_received' => $after_charge,
                'recheck'         => 0,
                'created_at'      => $request->date,

        ]);

        $total_amount = Payment::find($payment_id)->totalAmount();
        $amount_paid  = PaymentType::where('payment_id', $payment_id)->sum('amount_paid');
        $dues         = $total_amount - $amount_paid;

        Payment::find($payment_id)->update(['dues'=>$dues]);

        return redirect()->route('payment.client.recheck.types.list');

    }

    public function bankAccount()
    {
        $data['active_class']         = 'payments';
        $bank_account_income_expenses = IncomeExpense::where('recheck', 0)->get();
        $bank_account_client          = PaymentType::where([
                                             'cheque_verified'          => 1,
                                             'online_verified'          => 1,
                                             'bkash_salman_verified'    => 1,
                                             'bkash_corporate_verified' => 1,
                                         ])->get();

        $banks = [

            'cash'           => 0,
            'cash_dhaka'     => 0,
            'cash_ctg'       => 0,
            'scb'            => 0,
            'city'           => 0,
            'dbbl'           => 0,
            'ebl'            => 0,
            'ucb'            => 0,
            'brac'           => 0,
            'agrani'         => 0,
            'icb'            => 0,
            'ucb_fdr'        => 0,
            'ucb_cc'         => 0,
            'brac_umrah'     => 0,
            'ucb_umrah'      => 0,
            'salman account' => 0,
            'kamran account' => 0

         ];

         $data['bank_accounts'] = [

            'cash'           => 'CASH',
            'cash_dhaka'     => 'CASH - DHAKA',
            'cash_ctg'       => 'CASH - CHITTAGONG',
            'scb'            => 'SCB',
            'city'           => 'CITY',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'AGRANI',
            'icb'            => 'ICB',
            'ucb_fdr'        => 'UCB_FDR',
            'ucb_cc'         => 'UCB_CC',
            'brac_umrah'     => 'BRAC_UMRAH',
            'ucb_umrah'      => 'UCB_UMRAH',
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
        $data['active_class']      = 'payments';
        $data['account']           = $account;
        
        $data['payment_histories'] = PaymentType::where('bank_name', $account)
                                       ->where('cheque_verified', 1)
                                       ->where('online_verified', 1)
                                       ->where('bkash_salman_verified', 1)
                                       ->where('bkash_corporate_verified', 1)
                                       ->get();

        $payment_histories         = $data['payment_histories'];

        $data['incomes_and_expenses'] = IncomeExpense::where([
            'bank_name' => $account,
            'recheck'   => 0,
        ])->get();

        $incomes_and_expenses        = $data['incomes_and_expenses'];


        $payment_breakdown = array();
        $index             = 0; 

        foreach ($payment_histories as $key => $value) {
            $payment_breakdowns[$index]['date']        = $value->created_at;
            $payment_breakdowns[$index]['location']    = $value->payment->location;
            $payment_breakdowns[$index]['client_code'] = (isset($value->payment->userInfo->client_code)) ? $value->payment->userInfo->client_code : 'Client Removed';
            $payment_breakdowns[$index]['client_name'] = (isset($value->payment->userInfo->name)) ? $value->payment->userInfo->name : 'Client Removed';
            $payment_breakdowns[$index]['type']        = (isset($value->payment->programInfo->program_name)) ? $value->payment->programInfo->program_name : 'Program Removed';
            
            if($value->refund_payment == 1) {
                $payment_breakdowns[$index]['paid']        = -$value->amount_paid;
                $payment_breakdowns[$index]['received']    = -$value->amount_received;
                $payment_breakdowns[$index]['description'] = (isset($value->payment->stepInfo->step_name)) ? $value->payment->stepInfo->step_name . '(Refund)': 'Step Removed';
            } else {
                $payment_breakdowns[$index]['paid']        = $value->amount_paid;
                $payment_breakdowns[$index]['received']    = $value->amount_received;
                $payment_breakdowns[$index]['description'] = (isset($value->payment->stepInfo->step_name)) ? $value->payment->stepInfo->step_name : 'Step Removed';
            }
            $payment_breakdowns[$index]['bank_charge'] = $value->bank_charge;
            
            $index++;
        }

        foreach ($incomes_and_expenses as $key => $value) {

            $payment_breakdowns[$index]['date']        = $value->created_at;
            $payment_breakdowns[$index]['location']    = $value->location;
            $payment_breakdowns[$index]['client_code'] = '';
            $payment_breakdowns[$index]['client_name'] = '';
            $payment_breakdowns[$index]['type']        = $value->payment_type;
            $payment_breakdowns[$index]['description'] = $value->description;
            $payment_breakdowns[$index]['paid']        = $value->total_amount;
            $payment_breakdowns[$index]['bank_charge'] = 0;
            $payment_breakdowns[$index]['received']    = $value->total_amount;
            $index++;

        }

        // return $payment_breakdowns;

        $data['total_amount'] = array_sum(array_column($payment_breakdowns, 'received'));
        $this->array_sort_by_column($payment_breakdowns, 'date');
        $data['payment_breakdowns'] = $payment_breakdowns;

        return view('payments.account_details', $data);
    }

    public function transfer(Request $request)
    {
        $date_timestamp = Carbon::parse($request->date)->toDateTimeString();

        IncomeExpense::create([
            'payment_type' => 'Cash Transfer In',
            'bank_name'    => $request->to_account,
            'total_amount' => $request->amount,
            'recheck'      => 0,
            'description'  => $request->description,
            'created_by'   => Auth::user()->id,
            'created_at'   => $date_timestamp, 
        ]);

        IncomeExpense::create([
            'payment_type' => 'Cash Transfer Out',
            'bank_name'    => $request->from_account,
            'total_amount' => -1 * $request->amount,
            'recheck'      => 0,
            'description'  => $request->description,
            'created_by'   => Auth::user()->id,
            'created_at'   => $date_timestamp, 
        ]);

        return redirect()->back();

    }

    public function createIncome()
    {
        $data['active_class'] = 'payment';
        $data['previous'] = URL::to('/dashboard');

        $data['bank_accounts'] = [
            'cash_dhaka'     => 'Cash - Dhaka',
            'cash_ctg'       => 'Cash - Chittagong',
            'scb'            => 'SCB',
            'city'           => 'City Bank',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'Agrani Bank',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

         $data['locations'] = [
            'dhaka'      => 'Dhaka', 
            'chittagong' => 'Chittagong',
            'cox_bazar'  => 'Cox\'s bazar',
            'sylhet'     => 'Sylhet',
            'khulna'     => 'Khulna',
            'comilla'    => 'Comilla',
            'noakhali'   => 'Noakhali',
            'rajshahi'   => 'Rajshahi',
            'bagura'   => 'Bagura',
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
            'payment_type'    => $request->type,
            'total_amount'    => $amount,
            'bank_name'       => $request->bank_name,
            'location'        => $request->location,
            'recheck'         => 1,
            'description'     => $request->description,
            'advance_payment' => $request->advance_payment,
            'created_by'      => Auth::user()->id,
            'created_at'      => $date_timestamp
        ]);

        return redirect()->back()->with('success', 'Entry created successfully!');

    }

    public function createExpense()
    {
        $data['active_class'] = 'payment';
        $data['previous']     = URL::to('/dashboard');
        
        $data['bank_accounts'] = [
            'cash_dhaka'     => 'Cash - Dhaka',
            'cash_ctg'       => 'Cash - Chittagong',
            'scb'            => 'SCB',
            'city'           => 'City Bank',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'Agrani Bank',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

         $data['locations'] = [
            'dhaka'      => 'Dhaka', 
            'chittagong' => 'Chittagong',
            'cox_bazar'  => 'Cox\'s bazar',
            'sylhet'     => 'Sylhet',
            'khulna'     => 'Khulna',
            'comilla'    => 'Comilla',
            'noakhali'   => 'Noakhali',
            'rajshahi'   => 'Rajshahi',
            'bagura'   => 'Bagura',
         ];

        return view('payments.create_expense', $data);
    }

    public function showIncomesAndExpenses($period = 'all') 
    {
        $data['active_class'] = 'payment';
        $data['previous']     = URL::to('/dashboard');

        $income_expenses = IncomeExpense::query();

        if($period == 60) {

            $from = Carbon::now()->subDays(60);
            $to   = Carbon::now();

            $income_expenses = $income_expenses->whereBetween('created_at', [$from, $to]);
        }

        $data['transactions'] = $income_expenses->orderBy('recheck', 'DESC')->get();

        $data['bank_accounts'] = [
            'cash_dhaka'     => 'Cash - Dhaka',
            'cash_ctg'       => 'Cash - Chittagong',
            'scb'            => 'SCB',
            'city'           => 'City Bank',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'Agrani Bank',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {
            return view('payments.admin.show_income_and_expenses', $data);
        }

        return view('payments.show_income_and_expenses', $data);
    }

    public function editIncomesAndExpenses(IncomeExpense $income_expense)
    {
        $data['active_class']   = 'incomes-expenses';
        $data['clients']        = User::userRole('client')->where('status', 'active')->get();
        $data['income_expense'] = $income_expense;

        $data['bank_accounts'] = [
            'cash_dhaka'     => 'Cash - Dhaka',
            'cash_ctg'       => 'Cash - Chittagong',
            'scb'            => 'SCB',
            'city'           => 'City Bank',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'Agrani Bank',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        return view('payments.admin.edit_income_and_expenses', $data);
    }

    public function showAdvanceIncomes()
    {
        $data['active_class'] = 'payments';

        $data['transactions'] = IncomeExpense::orderBy('recheck', 'DESC')->where([
            'payment_type'    => 'income',
            'advance_payment' => 'yes',
        ])->get();

        $data['bank_accounts'] = [
            'cash_dhaka'     => 'Cash - Dhaka',
            'cash_ctg'       => 'Cash - Chittagong',
            'scb'            => 'SCB',
            'city'           => 'City Bank',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'Agrani Bank',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

         if(Auth::user()->user_role == 'admin') {

            return view('payments.admin.show_advance_income', $data);

         } else if(Auth::user()->user_role == 'accountant') {

            return view('payments.accountant.show_advance_income', $data);

         } else {

            abort(500, 'Something went wrong');
         }

        
    }

    public function showAdvanceExpenses()
    {
        $data['active_class'] = 'payments';

        $data['transactions'] = IncomeExpense::orderBy('recheck', 'DESC')->where([
            'payment_type'    => 'expense',
            'advance_payment' => 'yes',
        ])->get();

        $data['bank_accounts'] = [
            'cash_dhaka'     => 'Cash - Dhaka',
            'cash_ctg'       => 'Cash - Chittagong',
            'scb'            => 'SCB',
            'city'           => 'City Bank',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'Agrani Bank',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

         if(Auth::user()->user_role == 'admin') {

            return view('payments.admin.show_advance_expense', $data);

         } else if(Auth::user()->user_role == 'accountant') {

            return view('payments.accountant.show_advance_expense', $data);

         } else {

            abort(500, 'Something went wrong');
         }

    }

    public function updateAdvanceIncomeExpense(Request $request)
    {
        $transaction_id = $request->transaction_id;

        if($request->select_option == 'full') {

            IncomeExpense::find($transaction_id)->update([
                'advance_payment' => 'no'
            ]);
        } else {
            IncomeExpense::find($transaction_id)->update([
                'cleared_amount' => $request->cleared_amount
            ]);
        }

        return redirect()->back();
    }

    public function updateIncomesAndExpenses(Request $request)
    {
        IncomeExpense::find($request->id)->update([
            'payment_type'    => $request->payment_type,
            'total_amount'    => $request->amount,
            'bank_name'       => $request->bank_name,
            'location'        => $request->location,
            'recheck'         => 1,
            'description'     => $request->description,
            'advance_payment' => $request->advance_payment,
            'created_at'      => Carbon::parse($request->date)->toDateTimeString()
        ]);

        return redirect()->back()->with('message', 'Transaction has been updated!');
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

            case 'cash_dhaka':
                return view('payments.types.cash_online', $data);
                break;

            case 'cash_ctg':
                return view('payments.types.cash_online', $data);
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

            case 'pay_gic':
                return view('payments.types.cash_online', $data);
                break;

            case 'pay_gic_ssl':
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
        $data['incomes']  = IncomeExpense::where('payment_type', 'income')->get();
        $data['expenses'] = IncomeExpense::where('payment_type', 'expense')->get();

        return view('payments.income_expense_pdf', $data);
    }

    public function clientRefund()
    {
        $data['previous']     = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['clients']      = User::userRole('client')->where('status', 'active')->get();
        $data['programs']     = Program::all();

        $data['bank_accounts'] = [
            'cash_dhaka'     => 'CASH - DHAKA',
            'cash_ctg'       => 'CASH - CHITTAGONG',
            'scb'            => 'SCB',
            'city'           => 'CITY',
            'dbbl'           => 'DBBL',
            'ebl'            => 'EBL',
            'ucb'            => 'UCB',
            'brac'           => 'BRAC',
            'agrani'         => 'AGRANI',
            'icb'            => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ];

        return view('payments.refund', $data);
    }

    public function storeClientRefund(Request $request)
    {
        $validatedData = $request->validate([
            'client_id'    => 'required|not_in:0',
            'program_id'   => 'required|not_in:0',
            'payment_type' => 'required|not_in:0',
            'bank_name'    => 'required',
            'amount'       => 'required|min:1',
        ]);

        PaymentType::create([
            'payment_id'      => $request->payment_id,
            'payment_type'    => $request->payment_type,
            'bank_name'       => $request->bank_name,
            'cheque_number'   => $request->cheque_number,
            'amount_paid'     => $request->amount,
            'amount_received' => $request->amount,
            'refund_payment'  => 1,
            'notes'           => $request->notes,
            'created_at'      => $request->date,

        ]);

        return redirect()->back()->with('success', 'Refund has been created!');

    }

    public function clientRefundHistory()
    {
        $data['active_class'] = 'dues';
        $user_role            = Auth::user()->user_role;

        if($user_role == 'counselor') {

            $counselor_id    = Auth::user()->id;
            $client_ids      = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $payment_ids     = Payment::whereIn('client_id', $client_ids)->pluck('id');
            $data['refunds'] = PaymentType::whereIn('payment_id', $payment_ids)->where('refund_payment', 1)->get();

        } else if($user_role == 'rm') {
            
            $rm_id           = Auth::user()->id;
            $client_ids      = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $payment_ids     = Payment::whereIn('client_id', $client_ids)->pluck('id');
            $data['refunds'] = PaymentType::whereIn('payment_id', $payment_ids)->where('refund_payment', 1)->get();

        } else {
            $data['refunds'] = PaymentType::where('refund_payment', 1)->get();
        }

        if($user_role == 'admin') {
            return view('payments.admin.refund_history', $data);
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
        $user                 = Auth::user();
        $data['previous']     = URL::to('/dashboard');
        $data['active_class'] = 'payments';

        if($user->user_role == 'counselor') {

            $counselor_id     = $user->id;
            $client_ids       = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');
            $data['all_dues'] = Payment::whereIn('client_id', $client_ids)->where('dues', '>', 0)->get();

        } else if($user->user_role == 'rm') {
            
            $rm_id            = $user->id;
            $client_ids       = RmClient::where('rm_id', $rm_id)->pluck('client_id');
            $data['all_dues'] = Payment::whereIn('client_id', $client_ids)->where('dues', '>', 0)->get();

        } else if($user->user_role == 'admin' || $user->user_role == 'accountant' || $user->user_role == 'operation' || $user->user_role == 'backend'){

            $data['all_dues'] = Payment::all();

        } else {
            abort(500, 'Something went wrong');
        }

        
        return view('payments.dues', $data);
    }

    public function clientDuesDetails(Payment $payment_id)
    {
        $data['previous']      = URL::to('/dashboard');
        $data['active_class']  = 'payments';
        $data['payment']       = $payment_id;
 
        $data['program_fee']   = $payment_id->opening_fee + 
                                 $payment_id->embassy_student_fee + 
                                 $payment_id->service_solicitor_fee + 
                                 $payment_id->other;

        $data['payment_types'] = PaymentType::where('payment_id', $payment_id->id)
                                 ->where('cheque_verified', '=', 1)
                                 ->where('online_verified', '=', 1)
                                 ->where('bkash_salman_verified', '=', 1)
                                 ->where('bkash_corporate_verified', '=', 1)
                                 ->where('refund_payment', '!=', 1)
                                 ->get();

        return view('payments.due_details', $data);
    }

    public function duePayment(Payment $payment)
    {
        $data['active_class'] = 'payments';
        $data['payment']      = $payment;
        $program_fee          = $payment->totalAmount();
        $payment_types        = $payment->totalVerifiedPayment();
        $data['total_amount'] = $program_fee - $payment_types->sum('amount_paid');

        return view('payments.due_payment', $data);
    }

    public function paymentNotes($client_id)
    {
        $data['active_class'] = 'payments';
        $data['client']       = User::find($client_id);
        $data['notes']        = PaymentNote::where('client_id', $client_id)->get();

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
            'date'        => $request->date,
            'description' => $request->description,
            'amount'      => $request->amount
        ]);

        return redirect()->back();
    }

    public function storeDuePayment(Request $request)
    {
        $counter      = 0;
        $counter      = $request->counter;
        $payment_id   = Input::get('payment_id');
        $bank_charges = BankCharge::all();

        for ($i=0; $i < $counter + 1; $i++) {
            $charge                   = 0;
            $cheque_verified          = 1;
            $online_verified          = 1;
            $bkash_salman_verified    = 1;
            $bkash_corporate_verified = 1;
            $payment_type             = Input::get('payment_type-' . $i);
            $bank_deposited           = Input::get('bank_name-' . $i);
            $after_charge             = 0;

            if($payment_type == 'card') {

                $POS_machine    = Input::get('pos_machine-' . $i);
                $city_bank      = Input::get('city_bank-' . $i);
                $card_type      = Input::get('card_type-' . $i);
                $charge         = 0;
                $bank_deposited = Input::get('bank_name-' . $i);

                switch ($POS_machine) {

                    case 'city':

                        $bank_deposited = 'scb';
                        if($card_type == 'amex') {
                            $charge = $bank_charges->where('bank_name', 'city_bank_amex')->first()->bank_charge;
                        } else {
                            if($city_bank == 'yes') {
                                $charge = $bank_charges->where('bank_name', 'city_bank')->first()->bank_charge;
                            } else {
                                $charge = $bank_charges->where('bank_name', 'city_bank_other')->first()->bank_charge;
                            }
                        }
                        
                        break;

                    case 'brac':

                        $bank_deposited = 'brac';
                        $charge = $bank_charges->where('bank_name', 'brac_bank')->first()->bank_charge;
                        break;

                    case 'ebl':
                        
                        $bank_deposited = 'ebl';
                        $charge = $bank_charges->where('bank_name', 'ebl')->first()->bank_charge;
                        break;

                    case 'ucb':
                        
                        $bank_deposited = 'ucb';
                        $charge = $bank_charges->where('bank_name', 'ucb')->first()->bank_charge;
                        break;

                    case 'dbbl':
                        
                        $bank_deposited = 'dbbl';
                        $charge = $bank_charges->where('bank_name', 'dbbl')->first()->bank_charge;
                        break;

                    default:
                    // Do nothing
                }

            } else if($payment_type == 'cheque') {

                $cheque_verified = -1;

            } else if($payment_type == 'online') {

                $online_verified = -1;

            } else if($payment_type == 'upay') {

                $charge = $bank_charges->where('bank_name', 'upay')->first()->bank_charge;

            } else if($payment_type == 'bkash_corporate') {

                $charge = $bank_charges->where('bank_name', 'bkash_corporate')->first()->bank_charge;
                $bkash_corporate_verified = -1;

            } else if($payment_type == 'bkash_salman') {

                $charge = $bank_charges->where('bank_name', 'bkash_salman')->first()->bank_charge;
                $bkash_salman_verified = -1;

            } else if($payment_type == 'pay_gic') {

                $charge = $bank_charges->where('bank_name', 'pay_gic')->first()->bank_charge;
                $online_verified = -1;

            } else if($payment_type == 'pay_gic_ssl') {

                $charge = $bank_charges->where('bank_name', 'pay_gic_ssl')->first()->bank_charge;
                $online_verified = -1;

            } else {

                // Do nothing
            }

            $amount_paid = Input::get('total_amount-' . $i);

            if($charge > 0) {
                $after_charge = $amount_paid - (($charge / 100) * $amount_paid);
            } else {
                $after_charge = $amount_paid;
            }

            $date = Carbon::parse(Input::get('date-' . $i))->toDateTimeString();

            PaymentType::create([
                'payment_id'               => $payment_id,
                'payment_type'             => $payment_type,
                'card_type'                => Input::get('card_type-' . $i),
                'name_on_card'             => Input::get('name_on_card-' . $i),
                'card_number'              => Input::get('card_number-' . $i),
                'expiry_date'              => Input::get('expiry_date-' . $i),
                'pos_machine'              => Input::get('pos_machine-' . $i),
                'approval_code'            => Input::get('approval_code-' . $i),
                'phone_number'             => Input::get('phone_number-' . $i),
                'cheque_number'            => Input::get('cheque_number-' . $i),
                'bank_name'                => $bank_deposited,
                'cheque_verified'          => $cheque_verified,
                'online_verified'          => $online_verified,
                'bkash_salman_verified'    => $bkash_salman_verified,
                'bkash_corporate_verified' => $bkash_corporate_verified,
                'due_payment'              => 1,
                'bank_charge'              => $charge,
                'amount_paid'              => Input::get('total_amount-' . $i),
                'amount_received'          => $after_charge,
                'created_at'               => $date,
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

        $due['name']                  = $client->name;
        $due['address']               = $client_additional_info->address;
        $due['country_of_choice']     = json_decode($client_additional_info->country_of_choice);
        $due['mobile']                = $client->mobile;
        $due['email']                 = $client->email;
        $due['date']                  = Carbon::now()->format('d-m-Y');
        $due['client_code']           = $client->client_code;
        $due['program']               = Program::find($payment->program_id)->program_name;
        $due['step']                  = Step::find($payment->step_id);
        $due['opening_fee']           = $payment->opening_fee;
        $due['embassy_student_fee']   = $payment->embassy_student_fee;
        $due['service_solicitor_fee'] = $payment->service_solicitor_fee;
        $due['other']                 = $payment->other;
        $due['amount_paid']           = PaymentType::where('payment_id', $payment->id)->sum('amount_paid');
        $due['payments']              = PaymentType::where('payment_id', $payment_id)->first();

        $created_by                   = User::find($payment->created_by);
        $due['created_by']            = $created_by ? $created_by->name : '';

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
            $client_ids   = CounsellorClient::where('counsellor_id', $counselor_id)->pluck('client_id');

            $data['due_payments'] = Payment::whereIn('client_id', $client_ids)->orwhereIn('id', $due_payment_type_ids)->get();

        } else if(Auth::user()->user_role == 'rm') {
            
            $rm_id      = Auth::user()->id;
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
        $data['active_class'] = 'unverified_payments';
        $data['unverified_cheques'] = PaymentType::where('payment_type', 'cheque')->where('cheque_verified', '!=', '1')->get();

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {

            return view('payments.admin.unverified_cheques', $data);

        } elseif($user_role == 'accountant') {

            return view('payments.accountant.unverified_cheques', $data);

        }

        return view('payments.unverified_cheques', $data);
    }

    public function onlinePayments()
    {
        $data['active_class']    = 'unverified_payments';
        $data['online_payments'] = PaymentType::where('payment_type', 'online')->where('online_verified', '!=', '1')->get();

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {

            return view('payments.admin.online_payments', $data);
            
        } elseif($user_role == 'accountant') {

            return view('payments.accountant.online_payments', $data);

        }

        return view('payments.online_payments', $data);

        
    }

    public function payGICPayments()
    {
        $data['active_class']     = 'unverified_payments';
        $data['pay_gic_payments'] = PaymentType::where('payment_type', 'pay_gic')->where('online_verified', '!=', '1')->get();

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {

            return view('payments.admin.pay_gic_payments', $data);
            
        } elseif($user_role == 'accountant') {

            return view('payments.accountant.pay_gic_payments', $data);

        }

        return view('payments.pay_gic_payments', $data);

        
    }

    public function payGICSSLPayments()
    {
        $data['active_class']         = 'unverified_payments';
        $data['pay_gic_ssl_payments'] = PaymentType::where('payment_type', 'pay_gic_ssl')->where('online_verified', '!=', '1')->get();

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {

            return view('payments.admin.pay_gic_ssl_payments', $data);
            
        } elseif($user_role == 'accountant') {

            return view('payments.accountant.pay_gic_ssl_payments', $data);

        }

        return view('payments.pay_gic_ssl_payments', $data);

        
    }

    public function unverifiedBkashSalman()
    {
        $data['active_class'] = 'unverified_payments';
        $data['unverified_bkashes_salman'] = PaymentType::where('payment_type', 'bkash_salman')
                                                         ->where('bkash_salman_verified', '!=', '1')
                                                         ->get();

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {

            return view('payments.admin.unverified_bkash_salman', $data);

        } 

        return view('payments.unverified_bkash_salman', $data);

    }

    public function unverifiedBkashCorporate()
    {
        $data['active_class']                 = 'unverified_payments';
        $data['unverified_bkashes_corporate'] = PaymentType::where('payment_type', 'bkash_corporate')
                                                ->where('bkash_corporate_verified', '!=', '1')
                                                ->get();

        $user_role = Auth::user()->user_role;

        if($user_role == 'admin') {
            return view('payments.admin.unverified_bkash_corporate', $data);
        }

        return view('payments.unverified_bkash_corporate', $data);

    }

    public function generateDuePDF(Payment $payment)
    {
        $due['payment'] = $payment;
        $pdf            = PDF::loadView('invoice.due', $due);

        return $pdf->download('due_clearance_invoice.pdf');
    }

    public function getClientPaymentId(Request $request)
    {
        $payment = Payment::where(
                            [
                                'client_id' => $request->client_id,
                                'program_id' => $request->program_id,
                                'step_id' => $request->step_id,
                            ])->first();

        $amount_paid_without_refunds = PaymentType::where('payment_id', '=', $payment->id)
                                            ->where('cheque_verified', '=', 1)
                                            ->where('online_verified', '=', 1)
                                            ->where('bkash_salman_verified', '=', 1)
                                            ->where('bkash_corporate_verified', '=', 1)
                                            ->where('refund_payment', '!=', 1)
                                            ->sum('amount_paid');

        $refunds = PaymentType::where('payment_id', '=', $payment->id)
                                            ->where('refund_payment', '=', 1)
                                            ->sum('amount_paid');

        $data['amount_paid'] = $amount_paid_without_refunds - $refunds;
        $data['payment_id']  = $payment->id;

        return $data;
    }

    public function updateChequeInfo(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'cheque_number'   => $request->cheque_number,
            'bank_name'       => $request->bank_name,
            'deposit_date'    => $request->deposit_date,
            'cheque_verified' => -1,
        ]);

        return redirect()->back();
    }

    public function updateOnlineInfo(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'bank_name'       => $request->bank_name,
            'deposit_date'    => $request->deposit_date,
            'online_verified' => -1,
        ]);

        return redirect()->back();
    }

    public function updateBkashSalmanInfo(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'deposit_date' => $request->deposit_date,
        ]);

        return redirect()->back();
    }

    public function updateBkashCorporateInfo(Request $request)
    {
        PaymentType::find($request->payment_id)->update([
            'deposit_date' => $request->deposit_date,
        ]);

        return redirect()->back();
    }

    public function updatePaymentNote(Request $request)
    {
        Payment::find($request->payment_id)->update([
            'comments' => $request->comments,
        ]);

        return redirect()->back();
    }

    public function getPaymentInfo(Request $request)
    {
        $data = Payment::find($request->payment_id);

        return response()->json($data);
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
                          ->where('bkash_salman_verified', '!=', 0)
                          ->where('bkash_corporate_verified', '!=', 0)
                          ->get();

        return $payment_types->sum('amount_paid');
    }

    public function findingTotalPaidAmountForOnline($payment_id)
    {
        $payment_types = PaymentType::where('payment_id', $payment_id)
                          ->where('cheque_verified', '!=', 0)
                          ->where('online_verified', '!=', 0)
                          ->where('bkash_salman_verified', '!=', 0)
                          ->where('bkash_corporate_verified', '!=', 0)
                          ->get();

        return $payment_types->sum('amount_paid');
    }

    public function getClientDues(Request $request)
    {
        $user            = User::find($request->client_id);

        $invoice_amount  = $user->getTotalInvoiceAmount();

        $amount_paid     = $user->getTotalVerifiedAmountPaid();

        $amount_refunded = $user->getTotalRefunds();

        $dues            = $invoice_amount - $amount_paid - $amount_refunded;

        return $dues;
    }

    public function updateTransactionConsent(Request $request)
    {
        IncomeExpense::find($request->trasaction_id)->update(['recheck' => $request->status]);

        $status = $request->status;

        return $status;
    }

    public function deleteTransaction(Request $request)
    {
        IncomeExpense::find($request->transaction_id)->delete();

        return 1;
    }

    public function array_sort_by_column(&$array, $column, $direction = SORT_ASC) {
        $reference_array = array();

        foreach($array as $key => $row) {
            $reference_array[$key] = $row[$column];
        }

        array_multisort($reference_array, $direction, $array);
    }

}