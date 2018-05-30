<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\RmClient;
use App\Program;
use App\ClientProgram;
use DB;
use Exception;

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
        return view('dashboard', $data);
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
        // try {

            DB::table('users')->insert([
                'client_code' => $request->client_code,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'user_role' => 'client',
            ]);

            $user = User::where('email', $request->email)->first();

            RmClient::insert([
                'client_id' => $user->id,
                'rm_id' => $request->rm_one
            ]);

            if ($request->rm) {
                foreach ($request->rm as $rm) {
                    RmClient::insert([
                        'client_id' => $user->id,
                        'rm_id' => $rm
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

            return redirect()->back()->with('message', 'Client Created!');
    }
}
