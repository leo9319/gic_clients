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
use DB;
use Exception;
use Mail;
use Auth;

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
        $data['active_class'] = 'dashboard';
        $data['number_of_clients'] = User::userRole('client')->count();
        $data['number_of_rms'] = User::userRole('rm')->count();
        $data['number_of_accountants'] = User::userRole('accountant')->count();
        $data['number_of_counsellor'] = User::userRole('counsellor')->count();

        if (Auth::user()->user_role == 'client') {
            $tasks = $data['tasks'] = ClientTask::where('client_id', Auth::user()->id)->get(); 
            $programs = $data['programs'] = ClientProgram::where('client_id', Auth::user()->id)->get(); 
            $completion_array = [];

            foreach ($programs as $key => $program) {
                foreach ($program->programInfo as $pi) {
                    $completion_array[$pi->program_name] = $this->programProgress(Auth::user()->id, $pi->id);
                }
            }

            $data['program_progresses'] = $completion_array;

            return view('dashboard.client', $data);
        }
        else {
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
        $roles = array('N/A', 'client', 'admin', 'guest');

        $assigned_role = $roles[$request->user_role_id];
        
        DB::table('users')->where('id', $id)->update(['user_role' => $assigned_role]);
        
        return redirect()->back();
    }

    public function createUser()
    {
        $data['rms'] = User::where('user_role', 'rm')->get();
        $data['counsellors'] = User::where('user_role', 'counsellor')->get();
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
            DB::table('users')->insert([
                'client_code' => $request->client_code,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'user_role' => 'client',
            ]);

            $user = User::where('client_code', $request->client_code)->first();

            RmClient::insert([
                'client_id' => $user->id,
                'rm_id' => $request->rm_one,
            ]);

            CounsellorClient::insert([
                'client_id' => $user->id,
                'counsellor_id' => $request->counsellor_one,
            ]);

            if ($request->rm) {
                foreach ($request->rm as $rm) {
                    RmClient::insert([
                        'client_id' => $user->id,
                        'rm_id' => $rm
                    ]);
                } 
            }

            if ($request->counsellor) {
                foreach ($request->counsellor as $counsellor) {
                    CounsellorClient::insert([
                        'client_id' => $user->id,
                        'counsellor_id' => $counsellor
                    ]);
                } 
            }

            if ($request->programs) {
                foreach($request->programs as $program) {
                    ClientProgram::insert([
                        'client_id' => $user->id,
                        'program_id' => $program
                    ]);
                }
            }

            $request->programs;
            if ($request->programs) {
                foreach($request->programs as $program) {
                    $program_tasks = Task::where('program_id', $program)->get();
                    foreach ($program_tasks as $program_task) {
                        // $program->id;
                        echo $program_task->id;
                        echo '<br>';
                        ClientTask::insert([
                            'client_id' => $user->id,
                            'program_id' => $program,
                            'task_id' => $program_task->id,
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
            $message->from('s.simab@gmail.com', 'GIC File Opened');
            $message->to($data['email']);
            $message->subject($data['subject']);
        });

        $phone = $request->mobile;
        $username = 'admin';
        $password = 'Generic!1234';
        $message ="Dear $request->name,\nYour file has been opened with GIC. Visit link $url Your password is $request->password.\nThank you.";

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
