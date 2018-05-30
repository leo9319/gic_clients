<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\ClientTask;
use App\RmClient;
use Storage;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin,rm,accountant')->only('create', 'store', 'assignClient', 'storeClientTasks');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['active_class'] = 'tasks';
        $data['tasks'] = Task::all();

        return view('tasks.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Task::create($request->all());
        
        return redirect()->back()->with('message', 'Task Created');;
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

    public function assignClient(User $client)
    {
        $data['active_class'] = 'clients';
        $data['client'] = $client;
        $data['tasks'] = Task::all();

        $client_task_array = array();
        $client_tasks = ClientTask::where('client_id', $client->id)->get();
        foreach($client_tasks as $index => $client_task){
            $client_task_array[$index] = $client_task->task_id;
        }

        $data['client_task_array'] = $client_task_array;

        // Time to get all the rm's assigned to the client from which table?? ;) Ans : rm_clients
        $client_rm_profiles = RmClient::getAssignedRms($client->id);

        $client_rm_id= [];

        foreach($client_rm_profiles as $index => $client_rm_profile) {
            array_push($client_rm_id, $client_rm_profile->rm_id);
        }

        $listOfRms = User::whereIn('id', $client_rm_id)->get()->pluck('name', 'id');
        $listOfRms[0] = 'Client';

        $data['rms'] = $listOfRms;

        return view('tasks.assign', $data);
    }

    public function storeClientTasks(Request $request, $client_id)
    {
        $rows = $request->task_array;
        
        ClientTask::where('client_id', $client_id)->delete();

        foreach ($rows as $row) {
            $date = 'date' . $row;

            ClientTask::insert([
                'client_id' => $client_id,
                'task_id' => $row,
                'assignee_id' => $request->$row,
                'assigned_date' => $request->$date
            ]);
        }

        return redirect()->back()->with('message', 'Task Assigned!');

    }

    public function storeFiles(Request $request, $client_id)
    {
        if ($request->file('image')) {
            // $image = $request->file('image');
            // $filename = 'file_' . $request->task_id . $client_id;
            // Storage::put('upload/images/' . $filename, file_get_contents($request->file('image')->getRealPath()));

            // echo $client_id;
            // echo $request->task_id;
           

            ClientTask::where([
                    'client_id'=> $client_id,
                    'task_id'=> $request->task_id
                ])
            ->update(['status'=>'complete']);


        }
        else {
            $filename = '';
        }

        if($request->status) {
            ClientTask::where([
                    'client_id'=> $client_id,
                    'task_id'=> $request->task_id
                ])
            ->update(['status'=>'complete']);
        }
        else if($request->status == NULL) {
            ClientTask::where([
                    'client_id'=> $client_id,
                    'task_id'=> $request->task_id
                ])
            ->update(['status'=>'incomplete']);
        }

        return redirect()->back();

    }
}
