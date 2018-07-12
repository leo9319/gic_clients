<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\ClientTask;
use App\ClientProgram;
use App\Program;
use App\TaskType;
use App\CounsellorClient;
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
        $this->middleware('role:admin,accountant,rm,operation,counsellor')->only('index');
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

    public function mytasks($program_id, $client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['program_id'] = $program_id;

        $data['client_tasks'] = ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id
        ])->get();

        $data['group_tasks'] = DB::table('group_tasks')->where([
            'program_id' => $program_id 
        ])->get();

        // $data['status'] = DB::table('client_group_tasks')->where([
        //     'client_id' => $client_id,
        //     'program_id' => $program_id, 
        //     'program_group_id' => $program_id,
        // ])->first()->status;


        return view('clients.tasks', $data);
    }

    public function programs($client_id)
    {
        $data['active_class'] =  'clients';
        $data['programs'] = ClientProgram::programs($client_id);
        $data['client'] = User::where('id', $client_id)->first();

        $group_tasks = $data['group_tasks'] = DB::table('group_tasks')
                                ->distinct()
                                ->orderBy('program_id', 'asc')
                                ->get(['program_id']);

        $programs = [];

        foreach ($group_tasks as $index => $value) {
                $programs[$index] = $value->program_id;
        }      

        $data['listed_programs'] = DB::table('programs')->whereIn('id', $programs)->get();   
        $data['listed_rms'] = User::where('user_role', 'rm')->get();   

        // echo $roles = collect(DB::table('client_group_tasks')->get())->keyBy('program_id');

        $data['program_group_id'] = DB::table('client_group_tasks')->where('client_id', $client_id)->get()->pluck('program_group_id', 'program_id');
        $data['assignee_id'] = DB::table('client_group_tasks')->where('client_id', $client_id)->get()->pluck('assignee_id', 'program_id');
 
        return view('clients.programs', $data);
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

        $client_group_completed_task = DB::table('client_group_tasks')->where([
            'client_id' => $client_id,
            'status' => 'complete'
        ])->get();

        $client_group_pending_task = DB::table('client_group_tasks')->where([
            'client_id' => $client_id,
            'status' => 'pending'
        ])->get();

        $pending_group_tasks = [];
        $completed_group_tasks = [];

        foreach ($client_group_pending_task as $index => $value) {
            $pending_group_tasks[$index] = $value->program_group_id;
        }

        foreach ($client_group_completed_task as $index => $value) {
            $completed_group_tasks[$index] = $value->program_group_id;
        }

        $data['client_group_pending_tasks'] = DB::table('group_tasks')->whereIn('program_id', $pending_group_tasks)->get();

        $data['client_group_completed_tasks'] = DB::table('group_tasks')->whereIn('program_id', $completed_group_tasks)->get();

        return view('profile.index', $data);
    }

    public function clientTasks($client_id, $program_id)
    {
        $data['active_class'] = 'rms'; 
        $data['client'] = User::find($client_id);
        $data['program'] = Program::find($program_id);

        // $data['all_tasks'] = DB::table('group_tasks')->where([
        //     'program_id' => $program_id
        // ])->get();

        // $data['individual_tasks'] = ClientTask::where([
        //     'client_id' => $client_id, 
        //     'program_id' => $program_id, 
        // ])->get();

        // Need to get all the group tasks that the client has completed

        // $group_task_id_completed = DB::table('client_group_tasks')->where([
        //     'client_id' => $client_id,
        //     'program_id' => $program_id,
        //     'status' => 'complete'
        // ])->first();

        // $group_task_id_pending = DB::table('client_group_tasks')->where([
        //     'client_id' => $client_id,
        //     'program_id' => $program_id,
        //     'status' => 'pending'
        // ])->first();

        // Get all the task with that program_id
        // if ($group_task_id_completed) {
        //     $data['complete_group'] = DB::table('group_tasks')->where('program_id', $group_task_id_completed->program_group_id)->get();
        // }
        // else {
        //      $data['complete_group'] = DB::table('group_tasks')->where('program_id', 0)->get();
        // }

        // if ($group_task_id_pending) {
        //     $data['pending_group'] = DB::table('group_tasks')->where('program_id', $group_task_id_pending->program_group_id)->get();
        // }
        // else {
        //      $data['pending_group'] = DB::table('group_tasks')->where('program_id', 0)->get();
        // }

        // individual completed tasks:

        // $data['individual_completed_tasks'] = ClientTask::where([
        //     'client_id' => $client_id,
        //     'program_id' => $program_id,
        //     'status' => 'complete'
        // ])->get();

        // $data['individual_pending_tasks'] = ClientTask::where([
        //     'client_id' => $client_id,
        //     'program_id' => $program_id,
        //     'status' => 'pending'
        // ])->get();

        // get all the rms:

        $data['all_rms'] = User::where('user_role', 'rm')->get();

        $data['all_tasks'] = ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
        ])->get();

        $data['pending_tasks'] = ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
            'status' => 'pending',
        ])->get();

        $data['complete_tasks'] = ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
            'status' => 'complete',
        ])->get();

        $data['task_types'] = TaskType::all();

        return view('tasks.client_tasks', $data);
    }

    public function completeGroupStore(Request $request, $client_id, $program_id) 
    {
        if ($request->check_all) {
            DB::table('client_group_tasks')->where([
                    'client_id' => $client_id,
                    'program_id' => $program_id
            ])->update(['status'=>'complete']);
        } else {
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
}