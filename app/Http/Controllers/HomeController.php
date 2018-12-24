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
use App\ClientFileInfo;
use App\Target;
use App\Payment;
use App\CounselorRmTask;
use App\DepartmentTarget;
use DB;
use Exception;
use Mail;
use Auth;
use PDF;
use Carbon\Carbon;
use URL;


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
        $this->middleware('role:admin,counselor,rm')->only('createUser', 'storeUser');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['previous'] = URL::to('/');
        $client_id = Auth::user()->id; 

        $data['active_class'] = 'dashboard';
        $data['number_of_clients'] = User::userRole('client')->get()->count();
        $data['number_of_rms'] = User::userRole('rm')->get()->count();
        $data['number_of_accountants'] = User::userRole('accountant')->get()->count();
        $data['number_of_counsellor'] = User::userRole('counselor')->get()->count();

        if (Auth::user()->user_role == 'client') {

            $data['appointments'] = Appointment::where('client_id', $client_id)->where('app_date', '>=', Carbon::now()->format('Y-m-d'))->get();

            $programs = ClientProgram::where('client_id', $client_id); 
            $data['programs'] = $programs->get();
            $data['program_count'] = $programs->count();
            $data['rm_client_count'] = RmClient::where('client_id', $client_id)->count();
            $data['counselor_client_count'] = CounsellorClient::where('client_id', $client_id)->count();
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

            $data['program_progresses'] = $completion_array;
            $client_all_tasks = ClientTask::where('client_id', $client_id);
            $data['client_tasks'] = $client_all_tasks->where('status', '!=', 'incomplete')->limit(5)->get();
            $data['client_incomplete_tasks'] = ClientTask::where('client_id', $client_id)->where('status', '=', 'incomplete')->limit(5)->get();
            $data['task_count'] = $client_all_tasks->count();

            return view('dashboard.client', $data);
        }

        elseif(Auth::user()->user_role == 'counselor' || Auth::user()->user_role == 'rm') {

            $user_id = Auth::user()->id;
            $todays_date = Carbon::now();
            $data['appointments'] = Appointment::where([
                                        'appointer_id' => $user_id,
                                        'app_date' => $todays_date->format('y-m-d')
                                    ])->get();
            
            $target = $data['target'] = Target::getUserTarget($user_id);

            if (Auth::user()->user_role == 'counselor') {

                $data['files_opened_this_month'] = CounsellorClient::fileOpenedThisMonth($user_id);
                $data['total_files_opened'] = CounsellorClient::totalFilesOpened($user_id);
                $data['department_target'] = DepartmentTarget::getCurrentMonthTarget('counseling');

            }
            else {

                $data['files_opened_this_month'] = RmClient::fileOpenedThisMonth($user_id);
                $data['total_files_opened'] = RmClient::totalFilesOpened($user_id); 
                $data['department_target'] = DepartmentTarget::getCurrentMonthTarget('processing');

            }

            return view('dashboard.rm_counsellor', $data);
        }
        
        else {
            $data['recent_clients'] = Payment::orderBy('created_at', 'desc')->limit(5)->get();
            $data['appointments'] = Appointment::limit(5)->where('app_date', '>', Carbon::now())->get();
            $data['targets'] = Target::limit(5)->orderBy('month_year', 'asc')->get();
            $data['payments'] = Payment::limit(5)->get();

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
        $data['users'] = User::gicStaffs();

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
        $data['programs'] = Program::latest('updated_at')->get();

        $last_entry = User::userRole('client')->where('client_code', 'like', 'CSM-%')->orderBy('client_code', 'desc')->first();
        

        if($last_entry != NULL) {

            $last_code = preg_replace("/[^0-9\.]/", '', $last_entry->client_code);
            $data['client_code'] = 'CSM-' . ($last_code + 1);
        }
        else {
            $data['client_code'] = 'CSM-000001'; 
        }

        return view('users.create', $data);
    }

    public function storeUser(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users',
            'programs' => 'required',
        ]);

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

        if ($request->numbers) {
            foreach($request->numbers as $number) {

                DB::table('additional_client_numbers')->insert([
                    'client_id' => $user->id,
                    'mobile' => $number
                ]);

            }
        }

        $file_info['creator_id'] = Auth::user()->id;
        $file_info['spouse_name'] = $request->spouse_name;
        $file_info['address'] = $request->address;
        $file_info['country_of_choice'] = json_encode($request->country_of_choice);
        $file_info['client_id'] = $user->id;

        ClientFileInfo::create($file_info);

        $all_rms = $this->addUserToArray($request->rm, $request->rm_one);
        $all_counselors = $this->addUserToArray($request->counselor, $request->counsellor_one);

        RmClient::assignRmToClient($all_rms, $user->id);
        CounsellorClient::assignCounselorToClient($all_counselors, $user->id);

        if ($request->programs) {
            foreach($request->programs as $program) {
                $first_step = Step::getProgramFirstStep($program);

                if($first_step) {
                    ClientProgram::create([
                        'client_id' => $user->id,
                        'program_id' => $program,
                        'steps' => json_encode(array($first_step->id)),
                    ]);
                } else {
                    ClientProgram::create([
                        'client_id' => $user->id,
                        'program_id' => $program,
                    ]);
                }
                
            }
        }


        if ($request->programs) {
            foreach($request->programs as $program) {

                $first_step = Step::getProgramFirstStep($program);

                if($first_step) {

                    // Getting all the task for the client
                    $program_tasks = Task::where([
                        'step_id' => $first_step->id,
                        'assigned_to' => 'client',
                    ])->get();

                    // Assigning task to client_tasks table
                    foreach ($program_tasks as $program_task) {
                        ClientTask::create([
                            'client_id' => $user->id,
                            'step_id' => $first_step->id,
                            'task_id' => $program_task->id,
                            'deadline' => Carbon::now()->addDays($program_task->duration),
                        ]);
                    }

                    // Get all the task for the rm
                    $program_tasks_for_rm = Task::where([
                        'step_id' => $first_step->id,
                        'assigned_to' => 'rm',
                    ])->get();

                    // Assigning task to counselor_tasks table

                    foreach ($all_rms as $rm) {
                        foreach ($program_tasks_for_rm as $program_task_for_rm) {
                            CounselorRmTask::create([
                                'client_id' => $user->id,
                                'user_id' => $rm,
                                'step_id' => $first_step->id,
                                'task_id' => $program_task_for_rm->id,
                                'deadline' => Carbon::now()->addDays($program_task_for_rm->duration),
                                'priority' => $program_task_for_rm->priority,
                            ]);
                        }
                    } 

                    $program_tasks_for_counselors = Task::where([
                        'step_id' => $first_step->id,
                        'assigned_to' => 'counselor',
                    ])->get();

                    foreach ($all_counselors as $counselor) {
                        foreach ($program_tasks_for_counselors as $program_task_for_counselor) {
                            CounselorRmTask::create([
                                'client_id' => $user->id,
                                'user_id' => $counselor,
                                'step_id' => $first_step->id,
                                'task_id' => $program_task_for_counselor->id,
                                'deadline' => Carbon::now()->addDays($program_task_for_counselor->duration),
                                'priority' => $program_task_for_counselor->priority,
                            ]);
                        }
                    }
                }
            }
        }

        $url = URL::to('/');
        $invoice_url = $_SERVER['SERVER_NAME'] . '/invoice/opening/' . $user->id;

        $data = [
            'client_name' => $request->name,
            'url' => $url,
            'invoice_url' => $invoice_url,
            'email' => $request->email,
            'password' => $request->password,
            'subject' => 'GIC File Opened'
        ];
            
        // Mail::send('mail.file_open', $data, function($message) use ($data) {
        //     $message->from('s.simab@gmail.com', 'GIC File Opened');
        //     $message->to($data['email']);
        //     $message->subject($data['subject']);
        // });

        $phone = $request->mobile;
        $username = 'admin';
        $password = 'Generic!1234';
        $message ="Dear $request->name,\nYour file has been opened with GIC. Visit link $url\nYour Client ID $request->client_code\n Your Email ID is $request->email and your password is $request->password.\nThank you.";

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

        return redirect()->route('dashboard');


    }

    public function customStaffRegisterUpdate(Request $request)
    {
        User::find($request->user_id)->update([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_role' => $request->user_role,
        ]);

        return redirect()->back();
    }

    public function customStaffRegisterStore(Request $request)
    {
        User::insert([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'user_role' => $request->user_role,
        ]);

        return redirect()->back();
    }

    public function addUserToArray($user_array = '', $user) 
    {
        if($user_array) {
            array_push($user_array, $user);
            $total_users = $user_array;
        } else {
            $total_users = (array)$user;
        }

        return $total_users;
    }

    public function getUserInformation(Request $request)
    {
        $data = User::find($request->user_id);

        return response()->json($data);
    }

    public function deletUser(Request $request)
    {
        User::find($request->user_id)->delete();

        return redirect()->back();
    }

}
