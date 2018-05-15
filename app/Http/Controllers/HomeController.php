<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;

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
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard');
    }

    public function home()
    {
        return view('home');
    }

    public function users()
    {
        $users = User::all();

        return view('users.index')
            ->with('users', $users);
    }

    public function updateUserRole(Request $request, $id)
    {
        $roles = array('N/A', 'client', 'admin', 'guest');

        $assigned_role = $roles[$request->user_role_id];
        
        DB::table('users')->where('id', $id)->update(['user_role' => $assigned_role]);
        
        return redirect()->back();
    }
}
