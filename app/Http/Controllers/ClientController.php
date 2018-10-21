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
use App\SpouseTask;
use App\ClientFileInfo;
use App\CounselorRmTask;
use App\Appointment;
use App\Comment;
use App\EmailSms;
use Carbon\Carbon;
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
        $this->middleware('role:admin,accountant,rm,operation,counselor,backend')->only('index');
    }
    
    public function index()
    {
        $data['active_class'] = 'clients';
        $data['clients'] = User::userRole('client')->get();
        $data['counselors'] = User::userRole('counselor')->get();

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
        $data['step'] = Step::find($step_id);
        $data['client'] = User::find($client_id);

        return view('clients.tasks', $data);
    }

    public function spousetasks($step_id, $client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['all_tasks'] = SpouseTask::getSpouseTask($step_id, $client_id);
        $data['step'] = Step::find($step_id);
        $data['client'] = User::find($client_id);

        return view('clients.spouse_tasks', $data);
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

    public function storeSpouseIndividualTask(Request $request, $step_id, $client_id)
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

        SpouseTask::create([
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
        $data['program'] = Program::find($program_id);

        return view('clients.steps', $data);

    }

    public function spouseSteps($program_id, $client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['assigned_steps'] = ClientProgram::assignedSteps($program_id, $client_id);
        $data['client'] = User::find($client_id);
        $data['steps'] = Step::getProgramAllStep($program_id);
        $data['program'] = Program::find($program_id);

        return view('clients.spouse_steps', $data);

    }

    public function storeSteps(Request $request, $program_id, $client_id)
    {
        $step_array = [];
        $step_id = (integer)$request->step_id;
        $client_programs = ClientProgram::assignedSteps($program_id, $client_id);

        if ($client_programs->count() > 0) {
            if(!empty($client_programs->steps)) {
                $step_array = json_decode($client_programs->steps);
            }
        } 

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
                [
                    'client_id' => $client_id,
                    'step_id' => $step_id,
                    'task_id' => $program_task->id,
                    'deadline' => Carbon::now()->addDays($program_task->duration),
                ]
            );
        }

        
        $all_rms = RmClient::getAssignedRms($client_id);
        $all_counselors = CounsellorClient::assignedCounselor($client_id);

        $program_tasks_for_rm = Task::where([
            'step_id' => $step_id,
            'assigned_to' => 'rm',
        ])->get();

        foreach ($all_rms as $rm) {
            foreach ($program_tasks_for_rm as $program_task_for_rm) {
                CounselorRmTask::updateOrCreate(
                    [
                        'client_id' => $client_id,
                        'user_id' => $rm->rm_id,
                        'step_id' => $step_id,
                        'task_id' => $program_task_for_rm->id,
                    ],
                    [
                        'client_id' => $client_id,
                        'user_id' => $rm->rm_id,
                        'step_id' => $step_id,
                        'task_id' => $program_task_for_rm->id,
                        'deadline' => Carbon::now()->addDays($program_task_for_rm->duration),
                        'priority' => $program_task_for_rm->priority,
                ]   );
            }
        } 

        $program_tasks_for_counselors = Task::where([
            'step_id' => $step_id,
            'assigned_to' => 'counselor',
        ])->get();

        foreach ($all_counselors as $counselor) {
            foreach ($program_tasks_for_counselors as $program_task_for_counselor) {
                CounselorRmTask::updateOrCreate(
                    [
                        'client_id' => $client_id,
                        'user_id' => $counselor->counsellor_id,
                        'step_id' => $step_id,
                        'task_id' => $program_task_for_counselor->id,
                    ],
                    [
                        'client_id' => $client_id,
                        'user_id' => $counselor->counsellor_id,
                        'step_id' => $step_id,
                        'task_id' => $program_task_for_counselor->id,
                        'deadline' => Carbon::now()->addDays($program_task_for_counselor->duration),
                        'priority' => $program_task_for_counselor->priority,
                    ]);
            }
        }

        return redirect()->back();

    }

    public function myPrograms($client_id)
    {
        $data['active_class'] = 'my-tasks';
        $data['client'] = User::where('id', $client_id)->first();
        $data['programs'] = ClientProgram::programs($client_id);
        $data['all_programs'] = Program::all();

        return view('clients.myprograms', $data);
    }

    public function spousePrograms($client_id)
    {
        $data['active_class'] = 'spouse';
        $data['programs'] = ClientProgram::programs($client_id);
        $data['client'] = User::find($client_id);
        $data['all_programs'] = Program::all();

        $spouse = ClientFileInfo::where('client_id', $client_id)->first();

        if($spouse) {

            $data['spouse_name'] = $spouse->spouse_name;

        } else {
            $data['spouse_name'] = 'Unknown';
        }
        

        return view('clients.spouse_programs', $data);

    }

    public function storeClientProgram(Request $request, $client_id)
    {
        // Auto assign first step:

        // $step = Step::getProgramFirstStep($request->program_id);

        // ClientProgram::updateOrCreate(
        //     ['client_id' => $client_id, 'program_id' => $request->program_id],
        //     ['steps' => json_encode(array($step->id))]
        // );

        // Just assigning the program

        ClientProgram::updateOrCreate(
            ['client_id' => $client_id, 'program_id' => $request->program_id]
        );

        return redirect()->back();
    }

    public function profile($client_id)
    {
        $data['active_class'] = 'rms';
        $data['client'] = User::find($client_id);
        $data['client_programs'] = ClientProgram::where('client_id', $client_id)->get();

        $data['program_progresses'] = $this->clientProgramProgress($client_id);

        // Time to get the appointments:

        $timeline = array();

        $index = 0;

        $appointments = Appointment::getClientsAppointments($client_id);

        foreach ($appointments as $appointment) {
            $timeline[(string)$appointment->updated_at] = "Has an appointment on " . Carbon::parse($appointment->app_date)->format('dS F, Y') . " at " . Carbon::parse($appointment->app_time)->format('g:i a') . " with " .  User::find($appointment->appointer_id)->name;

        }

        $pending_tasks = ClientTask::getTasks($client_id, 'pending');

        foreach ($pending_tasks as $pending_task) {
            $timeline[(string)$pending_task->updated_at] = "Has the task: <b>" . Task::find($pending_task->task_id)->task_name . "</b> waiting for approval.";

        }

        $completed_tasks = ClientTask::getTasks($client_id, 'complete');

        foreach ($completed_tasks as $completed_task) {
            $timeline[(string)$completed_task->updated_at] = "Has <span class='text-success'>completed</span> the task: <b>" . Task::find($completed_task->task_id)->task_name . "</b> approved by " . User::find($completed_task->approved_by)->name ;

        }

        $comments = Comment::getAllCommentsOfCommenter($client_id);

        foreach ($comments as $comment) {
            $timeline[(string)$comment->updated_at] = "Has commented <q> <i>" . $comment->comment . "</i></q> on " . $comment->activity_type . "<q> " . $comment->getActivityName($comment->activity_type, $comment->activity_id)  . "</q>" ;

        }

        $texts = EmailSms::where('to', $client_id)->get();

        foreach ($texts as $text) {
            $timeline[(string)$text->updated_at] = "Was sent " . $text->type . " <q>" . $text->text_body . "</q> by " . User::find($text->from)->name ;

        }

        

        krsort($timeline);

        $data['timelines'] = $timeline;
        $data['previous'] = url()->previous();

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
        $data['assigned_councelors'] = CounsellorClient::where('client_id', $client_id)->get();
        $data['client'] = User::find($client_id);
        $data['counselors'] = User::where('user_role', 'counselor')->get();
 
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

    public function clientProgramProgress($client_id)
    {
        $completion_array = [];

        $client_program_steps = ClientProgram::where('client_id', $client_id)->pluck('steps', 'program_id')->toArray(); 

        foreach ($client_program_steps as $program => $steps) {

            foreach (json_decode($steps) as $step) {
                $all_task_count = 0;
                $complete_task_count = 0;

                $all_tasks =  ClientTask::where([
                    'step_id' => $step,
                    'client_id' => $client_id,
                ]);

                $all_task_count +=  $all_tasks->count();
                $complete_task_count += $all_tasks->where('status', 'complete')->count();
            }

            if($all_task_count != 0) {
                $completion_array[$program] = ($complete_task_count / $all_task_count) * 100;
            } else {
                $completion_array[$program] = 0;
            }

            
        }

        return $completion_array;
    }

    public function assingedCounselor($client_id)
    {
        $data['active_class'] = 'client-tasks';
        $data['client'] = User::find($client_id);
        $data['counselors'] = CounsellorClient::assignedCounselor($client_id);

        return view('clients.assigned_counselor', $data);
    }

    public function assingedRm($client_id)
    {
        $data['active_class'] = 'client-tasks';
        $data['client'] = User::find($client_id);
        $data['rms'] = RmClient::assignedRm($client_id);

        return view('clients.assigned_rm', $data);
    }

    public function clientDestroy($client_id)
    {
        User::find($client_id)->delete();

        return redirect()->back();
    }

    public function clientEdit($client_id) 
    {
        $data['client'] = User::find($client_id);
        $data['programs'] = Program::all();
        $data['client_add'] = ClientFileInfo::where('client_id', $client_id)->first();
        $data['client_programs'] = ClientProgram::where('client_id', $client_id)->get();
        $data['adddional_numbers'] = DB::table('additional_client_numbers')->where('client_id', $client_id)->get();

        return view('users.edit', $data);
    }

    public function clientUpdate(Request $request)
    {

        User::where('client_code', $request->client_code)->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email
        ]);

        return redirect()->back();
    }

    public function getClientName(Request $request)
    {
        // $data = DB::table('users AS U')
        //         ->join('client_programs AS CP', 'CP.client_id', '=', 'U.id')
        //         ->join('programs AS P', 'P.id', '=', 'CP.program_id')
        //         ->select('CP.program_id', 'P.program_name', 'U.name', 'U.id')
        //         ->where('U.id', $request->client_id)
        //         ->get();

        $data = User::find($request->client_id);

        return response()->json($data);
    }

    public function statement()
    {
        echo 'test';
    }

    
}