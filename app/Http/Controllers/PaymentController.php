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
use Carbon;
use Auth;
use PDF;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
            'opening_fee' => $request->opening_fee,
            'embassy_student_fee' => $request->embassy_student_fee,
            'service_solicitor_fee' => $request->service_solicitor_fee,
            'other' => $request->other,
            'total_amount' => $total_amount,
            'amount_paid' => $request->amount_paid,
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

        foreach ($program_tasks as $program_task) {
            ClientTask::updateOrCreate(
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $program_task->id],
                ['client_id' => $client_id, 'step_id' => $step_id, 'task_id' => $program_task->id]
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

        $client = User::find($client_id);
        $client_additional_info = ClientFileInfo::where('client_id', $client_id)->first();

        $data['name'] = $client->name;
        $data['address'] = $client_additional_info->address;
        $data['country_of_choice'] = json_decode($client_additional_info->country_of_choice);
        $data['mobile'] = $client->mobile;
        $data['email'] = $client->email;
        $data['date'] = Carbon\Carbon::now()->format('d-m-Y');
        $data['client_code'] = $client->client_code;
        $data['created_by'] = Auth::user()->name;
        $data['program'] = Program::find($request->program_id)->program_name;
        $data['step_number'] = $request->step_no;
        $data['opening_fee'] = $request->opening_fee;
        $data['embassy_student_fee'] = $request->embassy_student_fee;
        $data['service_solicitor_fee'] = $request->service_solicitor_fee;
        $data['other'] = $request->other;
        $data['amount_paid'] = $request->amount_paid;

        $pdf = PDF::loadView('invoice.index', $data);
        return $pdf->download('invoice.pdf');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function edit(Payment $payment)
    {
        //
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
        $data['active_class'] = 'payments';
        $data['payments'] = Payment::orderBy('created_at', 'desc')->get();

        return view('payments.history', $data);
    }
}
