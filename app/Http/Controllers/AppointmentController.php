<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class AppointmentController extends Controller
{
    public function clientWithRm()
    {
    	$data['active_class'] = 'appointments';
    	$data['rms'] = User::userRole('rm');

    	

      /*  $data['active_class'] = 'appointments';*/
        $data['counsellors'] = User::userRole('counsellor');

        return view('appointment.client_rm',$data);
    }

    public function clientWithCounsellor()
    {
    	$data['active_class'] = 'appointments';
    	$data['counsellors'] = User::userRole('counsellor');

    	return view('appointment.client_counsellor', $data);
    }
}
