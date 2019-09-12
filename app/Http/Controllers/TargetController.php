<?php

namespace App\Http\Controllers;

use App\Target;
use App\User;
use App\Program;
use App\Payment;
use App\DepartmentTarget;
use App\TargetSetting;
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
        $data['active_class'] = 'department-targets';
        $data['targets']      = DepartmentTarget::all();
        
        return view('targets.department', $data);
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
        $data['previous']     = URL::to('/target/counselor');
        $data['active_class'] = 'set-targets';
        $data['records']      = Target::where('user_id', $user_id)->orderBy('month_year','DESC')->get();
        $data['user']         = User::find($user_id);

        return view('targets.set_targets', $data);
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

    public function storeTarget(Request $request, $user_id)
    {
        $data['user_id']    = $user_id;
        $data['target']     = $request->target;
        $data['month_year'] = $request->month_year . '-01';

        Target::updateOrCreate(
            [
                'user_id'    => $data['user_id'], 
                'month_year' => $data['month_year']],
            [
                'target'     => $request->target
            ]
        );

        return redirect()->back();
    }

    public function show(User $user)
    {
        // return Payment::all();

         $month_and_year = Payment::get()->groupBy(function($d) {
             return Carbon\Carbon::parse($d->created_at)->format('Y');
         });

         return $month_and_year;
        // $data['active_class'] = 'my-targets';

        // if($user->user_role == 'rm') {

        //     $data['department_targets'] = DepartmentTarget::where('department', 'processing')->get();

        // } else {

        //     $data['department_targets'] = DepartmentTarget::where('department', 'counseling')->get();

        // }

        // return view('targets.show', $data);
        
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
                    ['step_id' => $key],
                    ['rm_count' => $target['rm_count'], 'counselor_count' => $target['counselor_count']],
                );
            }
            
        }

        return redirect()->back()->with(['success' => 'Target setting updated!']);
    }
}
