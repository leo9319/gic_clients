<?php

namespace App\Http\Controllers;

use App\Target;
use App\User;
use App\Program;
use App\Payment;
use App\DepartmentTarget;
use App\TargetSetting;
use Auth;
use Illuminate\Http\Request;
use Carbon;
use URL;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('department', 'rm', 'counselor', 'targetSetting');
    }

    public function department()
    {
        $data['active_class']       = 'department-targets';
        $data['department_targets'] = DepartmentTarget::latest()->get();
        
        return view('targets.department', $data);
    }

    public function storeDepartmentTarget(Request $request)
    {
        $validatedData = $request->validate([
            'department'    => 'required',
            'duration_type' => 'required',
            'target'        => 'required',
        ]);

        $duration_type = $request->duration_type;

        if ($duration_type == 'range') {

            DepartmentTarget::updateOrCreate(
            [
                'steps'      => $request->steps,
                'department' => $request->department,
                'start_date' => $request->start_date,
                'end_date'   => $request->end_date,
            ],
            [
                'target'     => $request->target
            ]);

        } elseif($duration_type == 'month') {

            DepartmentTarget::updateOrCreate(
            [
                'steps'      => $request->steps,
                'department' => $request->department,
                'month'      => $request->month . '-01'
            ],
            [
                'department' => $request->department,
                'month'      => $request->month . '-01',
                'target'     => $request->target
            ]);

        } else {

            return 0;

        }

        return redirect()->back();
    }

    public function showDepartmentTargetDetails(DepartmentTarget $department_target)
    {
        $data['active_class'] = 'department-targets';
        $data['department']   = $department_target->department;
        
        if ($department_target->month) {
            $data['payments'] = Payment::getMonthyPayment($department_target->month);
        } else {
            $data['payments'] = Payment::getPaymentWithDateRange($department_target->start_date, $department_target->end_date);
        }

        return view('targets.target_details', $data);
    }

    public function showIndividualTargetDetails(Target $target)
    {
        $data['active_class'] = 'set-targets';
        $data['user_id'] = $target->user_id;
        $month_and_year = $target->month_year;

        if($target->month_year) {
            $data['payments'] = Payment::getMonthyPayment($target->month_year);
        } else {
            $data['payments'] = Payment::getPaymentWithDateRange($target->start_date, $target->end_date);
        }

        if($target->user->user_role == 'rm') {
            return view('targets.individual_rm_target_details', $data);
        }

        return view('targets.individual_counselor_target_details', $data);

        
    }

    public function rm()
    {
        $data['previous']     = url()->previous();
        $data['active_class'] = 'set-targets';
        $data['rms']          = User::userRole('rm')->get();

        return view('targets.rm', $data);
    }

    public function counselor()
    {
        $data['previous']     = url()->previous();
        $data['active_class'] = 'set-targets';
        $data['counselors']   = User::userRole('counselor')->get();
        
        return view('targets.counselor', $data);
    }

    public function setTarget($user_id)
    {
        $data['active_class'] = 'set-targets';
        $data['targets']      = Target::where('user_id', $user_id)->orderBy('month_year','DESC')->get();
        $data['user']         = User::find($user_id);

        return view('targets.set_targets', $data);
    }

    public function storeTarget(Request $request, $user_id)
    {
        $validatedData = $request->validate([
            'duration_type' => 'required',
            'target'        => 'required',
        ]);

        $duration_type = $request->duration_type;

        if ($duration_type == 'range') {

            Target::updateOrCreate(
                [
                    'user_id'    => $user_id, 
                    'start_date' => $request->start_date,
                    'end_date'   => $request->end_date,
                ],
                [
                    'target'     => $request->target
                ]
            );

        } elseif($duration_type == 'month') {

            Target::updateOrCreate(
                [
                    'user_id'    => $user_id, 
                    'month_year' => $request->month . '-01',
                ],
                [
                    'target'     => $request->target
                ]
            );

        } else {

            return 0;

        }

        return redirect()->back();
    }

    public function show()
    {
        $data['active_class'] = 'my-targets';
        $user                 = Auth::user();
        $data['targets']      = Target::where('user_id', $user->id)->get();

        return view('targets.show', $data);
        
    }

    public function targetSetting()
    {
        $data['active_class'] = 'set-targets';
        $data['programs']     = Program::all();

        return view('targets.target_settings', $data);
    }

    public function storeTargetSetting(Request $request)
    {
        $targets = $request->all();

        foreach ($targets as $key => $target) {

            if($key != '_token') {

                TargetSetting::updateOrCreate(
                    ['step_id'  => $key],
                    ['rm_count' => $target['rm_count'], 'counselor_count' => $target['counselor_count']],
                );
            }
            
        }

        return redirect()->back()->with(['success' => 'Target setting updated!']);
    }
}
