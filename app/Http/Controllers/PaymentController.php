<?php

namespace App\Http\Controllers;

use App\Payment;
use Illuminate\Http\Request;
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
use Carbon;
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
        $this->middleware('role:admin')->only('bankAccount', 'accountDetails');
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


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'program_id' => 'required',
            'step_id' => 'required',
            'total_amount' => 'required',
            'payment_type' => 'required'
        ]);

        $POS_machine = $request->pos_machine;
        $bank_card = $request->bank_card;
        $card_type = $request->card_type;
        $charge = 0;
        $bank_deposited = $request->bank_name;
        $cheque_verified = 1;

        // If the payment is taken by POS machine

        switch ($POS_machine) {

            case 'city':

                $bank_deposited = 'scb';

                if($bank_card == 'city') {

                    if($card_type == 'amex') {
                        $charge = 2.5;
                    } elseif($card_type == 'visa' || $card_type == 'master') {
                        $charge = 1;
                    } else {
                        $charge = 2;
                    }
                    
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
        } elseif($request->payment_type == 'cheque') {
            $cheque_verified = -1;
        }

        if($charge > 0) {
            $after_charge = $request->amount_paid - (($charge / 100) * $request->amount_paid);
        } else {
            $after_charge = $request->amount_paid;
        }

        Payment::create([
            'client_id' => $request->client_id,
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
            'bank_charges' => $charge,
            'total_after_charge' => $after_charge,
            'amount_paid' => $request->amount_paid,
            'cheque_verified' => $cheque_verified,
            'recheck' => 0,
            'due_clearance_date' => $request->due_clearance_date,
            'created_by' => Auth::user()->id,
        ]);

        $program_id = $request->program_id;
        $client_id = $request->client_id;
        $order = $request->step_no;

        // $step_info = Step::getStepInfo($program_id, $order);
        $step_id = (integer)$request->step_id;

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

         
        ClientProgram::updateOrCreate(
            ['client_id' => $client_id, 'program_id' => $program_id],
            ['steps' => json_encode($step_array)]
        );

        $program_tasks = Task::getUserTasks($step_id, 'client');
        $spouse_program_tasks = Task::getUserTasks($step_id, 'spouse');

        foreach ($program_tasks as $program_task) {
            ClientTask::updateOrCreate(
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $program_task->id],
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $program_task->id]
            );
        }

        foreach ($spouse_program_tasks as $spouse_program_task) {
            SpouseTask::updateOrCreate(
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $spouse_program_task->id],
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $spouse_program_task->id]
            );
        }

        $client_id = $request->client_id;
        $associated_rms = RmClient::where('client_id', $client_id)->pluck('rm_id')->toArray();
        $associated_counselors = CounsellorClient::where('client_id', $client_id)->pluck('counsellor_id')->toArray();

        foreach ($associated_rms as $associated_rm) {
            Target::addOneToTarget($associated_rm);
        }

        foreach ($associated_counselors as $associated_counselor) {
            Target::addOneToTarget($associated_counselor);
        }

        return redirect()->back()->with('success', 'Payment Created Successfully!'); 

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

    public function verification(Payment $payment)
    {
        Payment::find($payment->id)->update(['recheck' => 0]);
        
        return redirect()->back();
    }

    public function disapprove(Payment $payment)
    {
        Payment::find($payment->id)->update(['recheck' => -1]);
        
        return redirect()->back();
    }

    public function chequeVerification(Payment $payment, $status)
    {
        Payment::find($payment->id)->update(['cheque_verified' => $status]);
        
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
        $data['date'] = Carbon\Carbon::now()->format('d-m-Y');
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

    public function recheck(Payment $payment)
    {
        Payment::find($payment->id)->update(['recheck' => '1']);

        return redirect()->back();
    }

    public function bankAccount()
    {
        $data['active_class'] = 'payments';
        $data['previous'] = URL::to('/dashboard');

        // Previous one:

        $data['payment_details'] = DB::table('payments')
                 ->select('bank_name', DB::raw('SUM(total_after_charge) AS total, SUM(total_amount) AS total_amount'))
                 ->groupBy('bank_name')
                 ->where('bank_name', '!=', 'NULL')
                 ->where('recheck', '=', 0)
                 ->where('cheque_verified', '=', 1)
                 ->get();


         $data['bank_accounts'] = [
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
        Payment::create([
            'payment_type' => 'Cash Transfer To',
            'bank_name' => $request->bank_name,
            'total_after_charge' => $request->amount,
            'amount_paid' => $request->amount,
            'cheque_verified' => 1,
            'recheck' => 0,
            'created_by' => Auth::user()->id,
        ]);

        Payment::create([
            'payment_type' => 'Cash Transfer from',
            'bank_name' => 'cash',
            'total_after_charge' => -1 * $request->amount,
            'amount_paid' => -1 * $request->amount,
            'cheque_verified' => 1,
            'recheck' => 0,
            'created_by' => Auth::user()->id,
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
        $date_timestamp = Carbon\Carbon::parse($request->date)->toDateTimeString();

        $amount = $request->amount;

        if($request->type == 'expense') {
            $amount = -($amount);
        }

        Payment::create([
            'payment_type' => $request->type,
            'total_amount' => $amount,
            'total_after_charge' => $amount,
            'bank_name' => $request->bank_name,
            'recheck' => 1,
            'description' => $request->description,
            'cheque_verified' => 1,
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

        $data['transactions'] = Payment::whereIn('payment_type', ['income', 'expense'])->get();

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
        $date_timestamp = Carbon\Carbon::parse($request->date)->toDateTimeString();

        $amount = $request->amount;

        if($request->type == 'expense') {
            $amount = -($amount);
        }

        Payment::find($request->payment_id)->update([
            'bank_name' => $request->bank_name,
            'total_amount' => $amount,
            'total_after_charge' => $amount,
            'recheck' => 1,
            'description' => $request->description,
            'created_at' => $date_timestamp,
        ]);

        return redirect()->back();
    }

    public function findIncomeAndExpenses(Request $request)
    {
        $data = Payment::where('id', $request->id)->first();

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

}
