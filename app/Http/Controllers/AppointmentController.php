<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Appointment;

class AppointmentController extends Controller
{
    public function index()
    {
        $data['active_class'] = 'appointments';
        $data['clients'] = User::userRole('client')->get();
        $data['rms'] = User::userRole('rm')->get();
        $data['counselors'] = User::userRole('counselor')->get();

        return view('appointment.index', $data);
    }

    public function setRmAppointment()
    {
        $data['active_class'] = 'appointments';
        $data['clients'] = User::userRole('client')->get();
        $data['rms'] = User::userRole('rm')->get();
        $data['counselors'] = User::userRole('counselor')->get();

        return view('appointment.rm_apointment', $data);
    }

    public function setCounselorAppointment()
    {
    	$data['active_class'] = 'appointments';
        $data['clients'] = User::userRole('client')->get();
        $data['rms'] = User::userRole('rm')->get();
        $data['counselors'] = User::userRole('counselor')->get();

    	return view('appointment.counselor_appointment', $data);
    }

    public function clientAppointment($client_id)
    {
        $data['active_class'] = 'appointments';
        $data['appointments'] = Appointment::getClientsAppointments($client_id);
        
        return view('appointment.client_appointments', $data);
    }
}
