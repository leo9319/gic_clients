<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use App\ClientTask;
use App\RmClient;
use App\Program;
use App\TaskType;
use App\Step;
use Auth;
use DB;
use Storage;
use Mail;

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
        $this->middleware('role:admin,rm,accountant,counsellor')->only('index', 'create', 'store', 'assignClient', 'storeClientTasks');
    }

    public function index()
    {
        $data['active_class'] = 'tasks'; 
        $data['programs'] = Program::all();

        return view('tasks.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        echo 'hello voxod';
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

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // all the tasks from the respective step_id
        $data['active_class'] = 'tasks';
        $data['tasks'] = Task::where('step_id', $id)->get();
        $data['step'] = Step::find($id);

        return view('tasks.show', $data);
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

    public function updateClientTask(Request $request)
    {
        $task_id = $request->task_id;
        $client_id = $request->status;
        $status = $request->status;

        ClientTask::updateStatus($task_id, $status);

        if ($request->file('uploaded_file_name')) {
            $file = $request->file('uploaded_file_name');
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = 'file_' . $task_id . $client_id .'.'. $ext;
            Storage::put('upload/images/' . $filename, file_get_contents($file->getRealPath()));

            ClientTask::find($task_id)->update([
                'uploaded_file_name' => $filename,
                'status' => 'pending',
            ]);
        }
    }

    public function assignClient($program_id, User $client)
    {
        $data['active_class'] = 'clients';
        $data['client'] = $client;
        $data['tasks'] = Task::all();

        $client_task_array = array();
        $client_task_date_array = array();
        $client_task_rm_array = array();

        $client_tasks = ClientTask::where([
            'client_id' => $client->id,
            'program_id' => $program_id
        ])->get();

        foreach($client_tasks as $index => $client_task){
            $client_task_array[$index] = $client_task->task_id;
            if($client_task->assigned_date) {
                $client_task_date_array[$client_task->task_id] = $client_task->assigned_date;
            }
            $client_task_rm_array[$client_task->task_id] = $client_task->assignee_id;
        }

        $data['client_task_array'] = $client_task_array;
        $data['client_task_date_array'] = $client_task_date_array;
        $data['client_task_rm_array'] = $client_task_rm_array;

        $client_rm_profiles = RmClient::getAssignedRms($client->id);

        $client_rm_id= [];

        foreach($client_rm_profiles as $index => $client_rm_profile) {
            array_push($client_rm_id, $client_rm_profile->rm_id);
        }

        $listOfRms = User::whereIn('id', $client_rm_id)->get()->pluck('name', 'id');
        $listOfRms[0] = 'Client';

        $data['rms'] = $listOfRms;
        $data['program_id'] = $program_id;

        return view('tasks.assign', $data);
    }

    public function storeClientTasks(Request $request, $program_id, $client_id)
    {
        $rows = $request->task_array;
        
        ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
        ])->delete();

        foreach ($rows as $row) {
            $date = 'date' . $row;
                        
            ClientTask::insert([
                'client_id' => $client_id,
                'program_id' => $program_id,
                'task_id' => $row,
                'assignee_id' => $request->$row,
                'assigned_date' => $request->$date
            ]);
        }

        return redirect()->back()->with('message', 'Task Assigned!');

    }

    public function storeFiles(Request $request, $program_id, $client_id)
    {
        $request->task_id;
        if($request->status) {
            ClientTask::where([
                    'id'=> $request->task_id,
                ])
            ->update(['status'=>'pending']);

            return redirect()->back();
        }
        else if($request->status == NULL) {
            ClientTask::where([
                    'id'=> $request->task_id,
                ])
            ->update(['status'=>'incomplete']);
        }

        if ($request->file('image')) {
            $image = $request->file('image');
            $ext = pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION);
            $filename = 'file_' . $request->task_id . $client_id .'.'. $ext;
            Storage::put('upload/images/' . $filename, file_get_contents($request->file('image')->getRealPath()));

            ClientTask::where([
                    'id'=> $request->task_id,
                ])
            ->update([
                'status'=>'pending',
                'uploaded_file_name'=>$filename
            ]);

            return redirect()->back();
        }

        else {
            ClientTask::where([
                    'id'=> $request->task_id,
                ])
            ->update([
                'status'=>'incomplete',
                'uploaded_file_name'=> ''
            ]);
        }

        return redirect()->back();

    }

    public function taskGroup($program_id)
    {
        $data['active_class'] = 'tasks';
        $data['program'] = Program::find($program_id);
        $data['task_types'] = TaskType::all();
        $data['group_tasks'] = DB::table('group_tasks')->where('program_id', $program_id)->get(); //not needed

        $data['program_tasks'] = Task::where('program_id', $program_id)->get(); 

        return view('tasks.groups', $data);
    }
    

    public function taskGroupStore(Request $request, $client_id, $program_id)
    {
        $program_group_id = $request->program_group_id;
        $assignee_id = $request->rms;

        DB::table('client_group_tasks')->where([
            'client_id' => $client_id,
            'program_id' => $program_id
        ])->delete();

        DB::table('client_group_tasks')->insert([
            'client_id' => $client_id,
            'program_id' => $program_id,
            'program_group_id' => $program_group_id,
            'assignee_id' => $assignee_id,
        ]);

        return redirect()->back();

    }

    public function taskTableGroupStore(Request $request, $program_id)
    {
        Task::create([
            'task_name' => $request->task_name,
            'type_id' => $request->type_id,
            'program_id' => $program_id,
        ]);

        return redirect()->route('task.group', $program_id);
    }

    public function assignGroupClient($program_id, User $client) 
    {
        $data['active_class'] = 'tasks';

        // Get all the unique program id
        $data['group_tasks'] = DB::table('group_tasks')
            ->distinct()
            ->orderBy('program_id', 'asc')
            ->get(['program_id']);
    }

    public function storeIndividualTasks(Request $request, $client_id, $program_id)
    {
        Task::create([
            'task_name' => $request->task_name,
            'type_id' => $request->type_id,
        ]);

        $task_id = Task::where([
            'task_name' => $request->task_name,
            'type_id' => $request->type_id,
        ])->first()->id;

        ClientTask::create([
            'client_id' => $client_id,
            'program_id' => $program_id,
            'task_id' => $task_id,
            'assignee_id' => $request->rm_id,
            'assigned_date' => $request->deadline,
        ]);

        return redirect()->back();
    }

    public function approval($client_task_id, $approval)
    {
        ClientTask::where('id', $client_task_id)
            ->update([
                'approval' => $approval,
                'status' => ($approval == 1) ? 'complete' : 'incomplete',
                'approved_by' => Auth::user()->id
            ]);

        $client_task = ClientTask::where('id', $client_task_id)->first();
        $client = User::find($client_task->client_id);
        $assignee = Auth::user();
        $task = Task::find($client_task->task_id);

        ($approval == 1) ? $status = 'approved' : $status = 'dissapproved';

        $data = [
            'client' => $client,
            'status' => $status,
            'assignee' => $assignee,
            'task' => $task,
            'email' => $client->email,
            'subject' => 'GIC Task Notification'
        ];

        Mail::send('mail.approval', $data, function($message) use ($data) {
            $message->from('s.simab@gmail.com', 'GIC Task Notification');
            $message->to($data['email']);
            $message->subject($data['subject']);
        });

        $phone = $client->mobile;
        $username = 'admin';
        $password = 'Generic!1234';
        $message ="Dear $client->name,\nThe task: $task->task_name has been $status by $assignee->name.For more information give is a call at 01778-000400.\nThank you.";

        $message = urlencode($message);

        $url = "http://gicl.powersms.net.bd/httpapi/sendsms?userId=form_sms&password=gicsms123&smsText=$message&commaSeperatedReceiverNumbers=$phone";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
        $response = curl_exec($ch);
        curl_close($ch);
        echo $phone . ': ';
        $response = str_replace('Success Count : 1 and Fail Count : 0', 'Sent Successfully!', $response);
        $response = str_replace('Success Count : 0 and Fail Count : 1', 'Failed!', $response);
        echo $response . '<br>';

        return redirect()->back();
    }

    public function downloadFile($file_name)
    {
        return Storage::download('upload/images/' . $file_name);
    }
}
