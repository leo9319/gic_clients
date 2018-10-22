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
        $total_amount = $request->opening_fee + $request->embassy_student_fee + $request->service_solicitor_fee + $request->other;
        
        Payment::create([
            'client_id' => $request->client_id,
            'program_id' => $request->program_id,
            'step_no' => $request->step_no,
            'payment_type' => $request->payment_type,
            'card_type' => $request->card_type,
            'name_on_card' => $request->name_on_card,
            'card_number' => $request->card_number,
            'expiry_date' => $request->expiry_date,
            'approval_code' => $request->approval_code,
            'bank_name' => $request->bank_name,
            'cheque_number' => $request->cheque_number,
            'phone_number' => $request->phone_number,
            'opening_fee' => $request->opening_fee,
            'embassy_student_fee' => $request->embassy_student_fee,
            'service_solicitor_fee' => $request->service_solicitor_fee,
            'other' => $request->other,
            'total_amount' => $request->total_amount,
            'amount_paid' => $request->amount_paid,
            'due_clearance_date' => $request->due_clearance_date,
            'created_by' => Auth::user()->id,
        ]);

        $program_id = $request->program_id;
        $client_id = $request->client_id;
        $order = $request->step_no;
        

        $step_info = Step::getStepInfo($program_id, $order);
        $step_id = (integer)$step_info->id;
        $client_programs = ClientProgram::assignedSteps($program_id, $client_id);
        $step_array = json_decode($client_programs->steps);

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
        $data['client_file_info'] = ClientFileInfo::find($payment->client_id);
        $data['program'] = Program::find($payment->program_id);
        $data['payment'] = $payment;
        $data['step'] = Step::find($payment->step_no);

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
        //
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
        $data['previous'] = url()->previous();
        $data['active_class'] = 'payments';
        $data['payments'] = Payment::orderBy('created_at', 'desc')->get();

        return view('payments.history', $data);
    }

    public function verification(Payment $payment)
    {
        Payment::find($payment->id)->update(['verified' => 1]);
        
        return redirect()->back();
    }

    public function chequeVerification(Payment $payment)
    {
        Payment::find($payment->id)->update(['cheque_verified' => 1]);
        
        return redirect()->back();

        echo $payment;
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
        $data['step_number'] = $payment->step_no;
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

}
