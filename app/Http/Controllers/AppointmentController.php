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

    	return view('appointment.client', $data);
    }
}
