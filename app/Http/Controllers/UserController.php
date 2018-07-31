<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\CounsellorClient;
use App\RmClient;

class UserController extends Controller
{
    public function myclients()
    {
    	$data['active_class'] = 'my-clients';

    	if(Auth::user()->user_role == 'counselor') {
    		$counselor_id = Auth::user()->id;
    		$data['assigned_clients'] = CounsellorClient::where('counsellor_id', $counselor_id)->get();

    		return view('users.client', $data);
    	}
    	else if (Auth::user()->user_role == 'rm')
    	{
    		$rm_id = Auth::user()->id;
    		$data['assigned_clients'] = RmClient::where('rm_id', $rm_id)->get();

    		return view('users.client', $data);
    	}
    }
}
