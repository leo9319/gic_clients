<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\RmClient;
use App\CounsellorClient;
use App\Program;
use App\ClientProgram;
use App\Task;
use App\ClientTask;
use App\Appointment;
use App\Step;
use DB;
use Exception;
use Mail;
use Auth;
use PDF;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('users', 'updateUserRole');
        $this->middleware('role:admin,accountant')->only('createUser', 'storeUser');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $client_id = Auth::user()->id; 

        $data['active_class'] = 'dashboard';
        $data['number_of_clients'] = User::userRole('client')->get()->count();
        $data['number_of_rms'] = User::userRole('rm')->get()->count();
        $data['number_of_accountants'] = User::userRole('accountant')->get()->count();
        $data['number_of_counsellor'] = User::userRole('counselor')->get()->count();

        if (Auth::user()->user_role == 'client') {
            $tasks = $data['tasks'] = ClientTask::where('client_id', $client_id)->get(); 
            $programs = $data['programs'] = ClientProgram::where('client_id', $client_id)->get(); 
            $completion_array = [];

            foreach ($programs as $key => $program) {
                foreach ($program->programInfo as $pi) {
                    $completion_array[$pi->program_name] = $this->programProgress($client_id, $pi->id);
                }
            }

            $data['appointments'] = Appointment::where('client_id', $client_id)->where('app_date', '>=', Carbon::now()->format('Y-m-d'))->get();
            $data['program_progresses'] = $completion_array;

            return view('dashboard.client', $data);
        }

        elseif(Auth::user()->user_role == 'counselor' || Auth::user()->user_role == 'rm') {
            $user_id = Auth::user()->id;
            $todays_date = Carbon::now();

            $data['appointments'] = Appointment::where([
                                        'appointer_id' => $user_id,
                                        'app_date' => $todays_date->format('y-m-d')
                                    ])->get();

            if (Auth::user()->user_role == 'counselor') {
                $data['files_opened_this_month'] = CounsellorClient::fileOpenedThisMonth($user_id);
                $data['total_files_opened'] = CounsellorClient::totalFilesOpened($user_id);
            }
            else {
                $data['files_opened_this_month'] = RmClient::fileOpenedThisMonth($user_id);
                $data['total_files_opened'] = RmClient::totalFilesOpened($user_id);
            }

            return view('dashboard.rm_counsellor', $data);
        }
        
        else {
            $data['recent_clients'] = User::userRole('client')->orderBy('created_at', 'desc')->limit(5)->get();
            $data['appointments'] = Appointment::limit(5)->where('app_date', '>', Carbon::now())->get();
            return view('dashboard.admin', $data);
        }
    }

    public function home()
    {
        return view('home');
    }

    public function users()
    {
        $data['active_class'] = 'users';
        $data['users'] = User::all();

        return view('users.index', $data);
    }

    public function updateUserRole(Request $request, $id)
    {
        $roles = array('N/A', 'admin', 'rm', 'accountant', 'backend', 'counselor', 'client');

        $assigned_role = $roles[$request->user_role_id];
        
        DB::table('users')->where('id', $id)->update(['user_role' => $assigned_role]);
        
        return redirect()->back();
    }

    public function createUser()
    {
        $data['rms'] = User::where('user_role', 'rm')->get();
        $data['counselors'] = User::where('user_role', 'counselor')->get();
        $data['programs'] = Program::all();

        $last_entry = DB::table('users')->orderBy('id', 'desc')->limit(1)->first();

        if($last_entry != NULL) {
            $data['client_code'] = 'CMS' . sprintf('%06d', ($last_entry->id + 1));
        }
        else {
            $data['client_code'] = 'CMS000001'; 
        }

        return view('users.create', $data);
    }

    public function storeUser(Request $request)
    {
        User::create([
            'client_code' => $request->client_code,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_role' => 'client',
            'remember_token' => str_random(60)
        ]);

        $user = User::where('client_code', $request->client_code)->first();

        RmClient::create([
            'client_id' => $user->id,
            'rm_id' => $request->rm_one,
        ]);

        CounsellorClient::create([
            'client_id' => $user->id,
            'counsellor_id' => $request->counsellor_one,
        ]);

        if ($request->rm) {
            foreach ($request->rm as $rm) {
                RmClient::create([
                    'client_id' => $user->id,
                    'rm_id' => $rm
                ]);
            } 
        }

        if ($request->counselor) {
            foreach ($request->counselor as $counselor) {
                CounsellorClient::create([
                    'client_id' => $user->id,
                    'counsellor_id' => $counselor
                ]);
            } 
        }

        if ($request->programs) {
            foreach($request->programs as $program) {
                ClientProgram::create([
                    'client_id' => $user->id,
                    'program_id' => $program
                ]);
            }
        }

        // Need some modification in here:-
        // 1. search the steps table for all the entries with the program_id of the respective program of the client.


        $request->programs;
        if ($request->programs) {
            foreach($request->programs as $program) {

                $first_step = Step::getProgramFirstStep($program);
                $program_tasks = Task::where([
                    'step_id' => $first_step->id,
                    'assigned_to' => 'client',
                ])->get();


                foreach ($program_tasks as $program_task) {
                    ClientTask::create([
                        'client_id' => $user->id,
                        'step_id' => $first_step->id,
                        'task_id' => $program_task->id
                    ]);
                }
            }
        }


        $url = 'https://ticklepicklebd.com/leos/gicclients';

        $data = [
            'client_name' => $request->name,
            'url' => $url,
            'email' => $request->email,
            'password' => $request->password,
            'subject' => 'GIC File Opened'
        ];
            
        Mail::send('mail.file_open', $data, function($message) use ($data) {
            $pdf = PDF::loadView('invoice.index');
            $message->from('s.simab@gmail.com', 'GIC File Opened');
            $message->to($data['email']);
            $message->subject($data['subject']);
        });

        $phone = $request->mobile;
        $username = 'admin';
        $password = 'Generic!1234';
        $message ="Dear $request->name,\nYour file has been opened with GIC. Visit link $url\nYour Email ID is $request->email and your password is $request->password.\nThank you.";

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
        $response = str_replace('Success Count : 1 and Fail Count : 0', 'Sent Successfully!', $response);
        $response = str_replace('Success Count : 0 and Fail Count : 1', 'Failed!', $response);

        return redirect()->back()->with('message', 'Client Created!');

        // echo Step::getProgramFirstStep(1);
    }

    public function customStaffRegister(Request $request)
    {
        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_role' => $request->user_role,
        ]);

        return redirect()->back();
    }

    public function programProgress($client_id, $program_id)
    {
        $tasks = ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
        ])->get();

        $ratio = ($tasks->where('status', 'complete')->count() / $tasks->count()) * 100;

        return $ratio;
    }
}
