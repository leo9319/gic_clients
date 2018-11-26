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
use App\IncomeExpense;
use Carbon\Carbon;
use Redirect;
use Auth;
use PDF;
use URL;
use DB;


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
        $this->middleware('role:admin')->only('bankAccount', 'accountDetails', 'recheck');
    }


    public function index()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['clients'] = User::userRole('client')->get();
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

        $payment = Payment::updateOrCreate(
            [
                'client_id' => $request->client_id,
                'program_id' => $request->program_id,
                'step_id' => $request->step_id
            ],
            [
                'opening_fee' => $request->opening_fee,
                'embassy_student_fee' => $request->embassy_student_fee,
                'service_solicitor_fee' => $request->service_solicitor_fee,
                'other' => $request->other,
                'created_by' => Auth::user()->id,
            ]);

        $data['total_amount'] = $request->opening_fee + $request->embassy_student_fee + $request->service_solicitor_fee + $request->other;

        $data['payment_id'] = $payment->id;

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
        $charge = 0;
        $counter = $request->counter;
        $payment_id = Input::get('payment_id');

        for ($i=0; $i < $counter + 1; $i++) { 

            $cheque_verified = 1;
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
                'bank_charge' => $charge,
                'amount_paid' => Input::get('total_amount-' . $i),
                'amount_received' => $after_charge,
            ]);
            
        }

        // If there is a due date

        if($request->due_date) {
            // there is a due date field

            Payment::find($payment_id)->update(['due_date' => Input::get('due_date')]);
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

        $data['payment_types'] = PaymentType::where('payment_id', $payment->id)->get();

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
            'payment_type' => 'required'
        ]);

        $POS_machine = $request->pos_machine;
        $bank_card = $request->banks_card;
        $card_type = $request->card_type;
        $charge = 0;
        $bank_deposited = $request->bank_name;

        // Payment taken by POS machine

        switch ($POS_machine) {

            case 'city':

                $bank_deposited = 'scb';

                if($bank_card == 'city') {

                    $charge = 1;
                    
                } else {

                    $charge = ($card_type == 'amex') ? 2.5 : 2;
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

        if($request->payment_type == 'bkash_corporate') {
            $charge = 1.5;
        } elseif($request->payment_type == 'bkash_salman') {
            $charge = 2;
        }


        if($charge > 0) {
            $after_charge = $request->amount_paid - (($charge / 100) * $request->amount_paid);
        } else {
            $after_charge = $request->amount_paid;
        }


        Payment::where('id', $payment->id)->update([
            'program_id' => $request->program_id,
            'step_id' => $request->step_id,
            'payment_type' => $request->payment_type,
            'card_type' => $request->card_type,
            'name_on_card' => $request->name_on_card,
            'card_number' => $request->card_number,
            'expiry_date' => $request->expiry_date,
            'approval_code' => $request->approval_code,
            'bank_name' => $bank_deposited,
            'cheque_number' => $request->cheque_number,
            'phone_number' => $request->phone_number,
            'opening_fee' => $request->opening_fee,
            'embassy_student_fee' => $request->embassy_student_fee,
            'service_solicitor_fee' => $request->service_solicitor_fee,
            'other' => $request->other,
            'total_amount' => $request->total_amount,
            'total_after_charge' => $after_charge,
            'bank_charges' => $charge,
            'amount_paid' => $request->amount_paid,
            'due_clearance_date' => $request->due_clearance_date,
        ]);

        return redirect()->back()->with('success', 'Payment Edited Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        //
    }

    public function paymentHistory()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'payments';
        $data['payments'] = Payment::orderBy('created_at', 'desc')->get();

        return view('payments.history', $data);
    }

    public function chequeVerification(PaymentType $payment_type, $status)
    {
        PaymentType::find($payment_type->id)->update(['cheque_verified' => $status]);
        
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
        $data['date'] = Carbon::now()->format('d-m-Y');
        $data['client_code'] = $client->client_code;
        $data['program'] = Program::find($payment->program_id)->program_name;
        $data['step'] = Step::find($payment->step_id);
        $data['opening_fee'] = $payment->opening_fee;
        $data['embassy_student_fee'] = $payment->embassy_student_fee;
        $data['service_solicitor_fee'] = $payment->service_solicitor_fee;
        $data['other'] = $payment->other;
        $data['amount_paid'] = $payment->amount_paid;

        $created_by = User::find($payment->created_by);
        $data['created_by'] = $created_by ? $created_by->name : '';

        $pdf = PDF::loadView('invoice.index', $data);
        return $pdf->download('invoice.pdf');
    }


    public function statement()
    {
        $data['active_class'] = 'payments';
        $data['previous'] = URL::to('/dashboard');
        $data['clients'] = User::userRole('client')->get();

        return view('payments.statement', $data);
    }

    public function showStatement($client_id)
    {
        $data['client'] = User::find($client_id);
        $data['client_info'] = ClientFileInfo::where('client_id', $client_id)->first();
        $data['rms'] = RmClient::getAssignedRms($client_id);
        $data['counselors'] = CounsellorClient::assignedCounselor($client_id);
        $data['payment_histories'] = Payment::where('client_id', $client_id)->get();

        return view('payments.show_statement', $data);
    }

    public function recheck($income_expenes_id, $status)
    {
        IncomeExpense::find($income_expenes_id)->update(['recheck' => $status]);

        return redirect()->back();
    }

    public function bankAccount()
    {
        $data['active_class'] = 'payments';
        $data['previous'] = URL::to('/dashboard');

        // Previous one:

        $bank_account_income_expenses = IncomeExpense::where('recheck', 0)->get();
        $bank_account_client = PaymentType::all();

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

        foreach ($bank_account_client as $bac_key => $bac_value) {

            foreach ($banks as $key => $value) {
                
                if($bac_value['bank_name'] == $key) {
                    $banks[$key] += $bac_value['amount_received'];
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

        $data['payment_histories'] = Payment::where('bank_name', $account)->get();

        return view('payments.account_details', $data);
    }

    public function transfer(Request $request)
    {
        $date_timestamp = Carbon::parse($request->date)->toDateTimeString();

        IncomeExpense::create([
            'payment_type' => 'Cash Transfer In',
            'bank_name' => $request->bank_name,
            'total_amount' => $request->amount,
            'recheck' => 0,
            'created_by' => Auth::user()->id,
            'created_at' => $date_timestamp, 
        ]);

        IncomeExpense::create([
            'payment_type' => 'Cash Transfer Out',
            'bank_name' => 'cash',
            'total_amount' => -1 * $request->amount,
            'recheck' => 0,
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
            'total_amount' => $amount,
            'recheck' => 1,
            'description' => $request->description,
            'created_at' => $date_timestamp,
        ]);

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
        $data['clients'] = User::userRole('client')->get();
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
            'bank_name' => 'required',
            'amount' => 'required|min:1',
        ]);

        return $request->all();
    }

}
