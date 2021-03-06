<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;
use App\Appointment;
use App\RmClient;
use App\CounsellorClient;

class AppointmentController extends Controller
{
    public function index()
    {
        $data['active_class']   = 'appointments';
        $data['clients']        = User::userRole('client')->get();
        $data['rms']            = User::userRole('rm')->get();
        $data['counselors']     = User::userRole('counselor')->get();

        return view('appointment.index', $data);
    }

    public function setRmAppointment()
    {
        $data['active_class']   = 'appointments';
        $data['clients']        = User::userRole('client')->get();
        

        if(Auth::user()->user_role == 'client') {

            $client_id          = Auth::user()->id;
            $rm_ids             = RmClient::getAssignedRms($client_id)->pluck('rm_id');
            $counselor_ids      = CounsellorClient::assignedCounselor($client_id)->pluck('counsellor_id');

            $data['rms']        = User::find($rm_ids);
            $data['counselors'] = User::find($counselor_ids);

        } else {

            $data['rms']        = User::userRole('rm')->get();
            $data['counselors'] = User::userRole('counselor')->get();

        }

        return view('appointment.rm_apointment', $data);
    }

    public function setCounselorAppointment()
    {
    	$data['active_class']   = 'appointments';
        $data['clients']        = User::userRole('client')->get();

        if(Auth::user()->user_role == 'client') {

            $client_id          = Auth::user()->id;
            $rm_ids             = RmClient::getAssignedRms($client_id)->pluck('rm_id');
            $counselor_ids      = CounsellorClient::assignedCounselor($client_id)->pluck('counsellor_id');

            $data['rms']        = User::find($rm_ids);
            $data['counselors'] = User::find($counselor_ids);

        } else {

            $data['rms']        = User::userRole('rm')->get();
            $data['counselors'] = User::userRole('counselor')->get();

        }

    	return view('appointment.counselor_appointment', $data);
    }

    public function clientAppointment($client_id)
    {
        $data['active_class']     = 'appointments';

        if(Auth::user()->user_role == 'client') {

            $data['appointments'] = Appointment::getClientsAppointments($client_id);

            return view('appointment.client_appointments', $data);

        } elseif(Auth::user()->user_role == 'rm' || Auth::user()->user_role == 'counselor') {

            $appointer_id         = Auth::user()->id;
            $data['appointments'] = Appointment::getUsersAppointments($appointer_id);

            return view('appointment.user_appointments', $data);
        }
        
    }
}
