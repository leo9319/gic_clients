<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ClientTask;
use App\ClientProgram;
use App\Program;
use App\TaskType;
use App\CounsellorClient;
use App\RmClient;
use App\Step;
use App\Task;
use DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,accountant,rm,operation,counsellor,backend')->only('index');
    }
    
    public function index()
    {
        $data['active_class'] = 'clients';
        $data['clients'] = User::where('user_role', 'client')->get();
        $data['counsellors'] = User::where('user_role', 'counsellor')->get();

        return view('clients.index', $data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function mytasks($step_id, $client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['all_tasks'] = ClientTask::getClientTask($step_id, $client_id);

        return view('clients.tasks', $data);
    }

    public function storeIndividualTask(Request $request, $step_id, $client_id)
    {
        Task::create([
            'task_name'=> $request->task_name,
            'form_name'=> $request->form_name,
            'file_upload'=> $request->file_upload
        ]);

        $task = Task::where([
            'task_name'=> $request->task_name,
            'form_name'=> $request->form_name,
            'file_upload'=> $request->file_upload,
        ])->first();

        ClientTask::create([
            'client_id' => $client_id,
            'step_id' => $step_id,
            'task_id' => $task->id,
            'deadline' => $request->deadline,
        ]);

        return redirect()->back();
    }

    public function mySteps($program_id, $client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['assigned_steps'] = ClientProgram::assignedSteps($program_id, $client_id);
        $data['client'] = User::find($client_id);
        $data['steps'] = Step::getProgramAllStep($program_id);

        return view('clients.steps', $data);

    }

    public function storeSteps(Request $request, $program_id, $client_id)
    {
        $step_id = (integer)$request->step_id;
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

        return redirect()->back();

    }

    public function myPrograms($client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['client'] = User::where('id', $client_id)->first();
        $data['programs'] = ClientProgram::programs($client_id);

        return view('clients.myprograms', $data);
    }

    public function profile($client_id)
    {
        $data['active_class'] = 'rms';
        $data['client'] = User::find($client_id);
        $data['client_complete_tasks'] = ClientTask::where([
                                'client_id' => $client_id,
                                'status' => 'complete'
                            ])->get();

        $data['client_pending_tasks'] = ClientTask::where([
                                'client_id' => $client_id,
                                'status' => 'pending'
                            ])->get();

        $data['client_programs'] = ClientProgram::where('client_id', $client_id)->get();

        $pending_group_tasks = [];
        $completed_group_tasks = [];

        $data['client_group_pending_tasks'] = DB::table('group_tasks')->whereIn('program_id', $pending_group_tasks)->get();

        $data['client_group_completed_tasks'] = DB::table('group_tasks')->whereIn('program_id', $completed_group_tasks)->get();

        return view('profile.index', $data);
    }

    public function completeGroupStore(Request $request, $client_id, $program_id) 
    {
        if ($request->check_all) {
            DB::table('client_group_tasks')->where([
                    'client_id' => $client_id,
                    'program_id' => $program_id
            ])->update(['status'=>'complete']);
        } 
        else {
            DB::table('client_group_tasks')->where([
                    'client_id' => $client_id,
                    'program_id' => $program_id
            ])->update(['status'=>'pending']);
        }

        return redirect()->back();
    }

    public function getClientCounsellor(Request $request)
    {
        $data = DB::table('counsellor_clients')
            ->join('users', 'counsellor_clients.counsellor_id', '=', 'users.id')
            ->where('client_id', '=', $request->client_id)
            ->get();

        return response()->json($data);
    }

    public function assignCounsellor($client_id)
    {
        $data['active_class'] = 'clients';
        $data['assigned_councellors'] = CounsellorClient::where('client_id', $client_id)->get();
        $data['client'] = User::find($client_id);
        $data['counsellors'] = User::where('user_role', 'counsellor')->get();
 
        return view('clients.assign_councellors', $data);
    }

    public function assignRm($client_id)
    {
        $data['active_class'] = 'clients';
        $data['assigned_rms'] = RmClient::where('client_id', $client_id)->get();
        $data['client'] = User::find($client_id);
        $data['rms'] = User::where('user_role', 'rm')->get();
 
        return view('clients.assign_rms', $data);
    }

    public function assignCounsellorStore(Request $request, $client_id)
    {
        CounsellorClient::updateOrCreate(
            ['client_id' => $client_id, 'counsellor_id' => $request->counsellor_one],
            ['client_id' => $client_id, 'counsellor_id' => $request->counsellor_one]
        );

        if ($request->counsellor) {
            foreach ($request->counsellor as $key => $value) {
                CounsellorClient::updateOrCreate(
                    ['client_id' => $client_id, 'counsellor_id' => $value],
                    ['client_id' => $client_id, 'counsellor_id' => $value]
                );
            }
        }

        return redirect()->back();
    }

    public function assignRmStore(Request $request, $client_id)
    {
        RmClient::updateOrCreate(
            ['client_id' => $client_id, 'rm_id' => $request->rm_one],
            ['client_id' => $client_id, 'rm_id' => $request->rm_one]
        );

        if ($request->rm) {
            foreach ($request->rm as $key => $value) {
                RmClient::updateOrCreate(
                    ['client_id' => $client_id, 'rm_id' => $value],
                    ['client_id' => $client_id, 'rm_id' => $value]
                );
            }
        }

        return redirect()->back();
    }

    public function action($client_id)
    {
        $data['active_class'] = 'clients'; 
        $data['client'] = User::find($client_id);

        return view('clients.actions', $data);
    }
}