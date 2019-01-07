<?php

namespace App\Http\Controllers;

use App\Target;
use App\User;
use App\DepartmentTarget;
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
        $this->middleware('role:admin')->only('department', 'rm', 'counselor');
    }

    public function department()
    {
        $data['previous'] = URL::to('/dashboard');
        $data['active_class'] = 'department-targets';
        $data['targets'] = DepartmentTarget::all();
        
        return view('targets.department', $data);
    }

    public function rm()
    {
        $data['previous'] = url()->previous();
        
        $data['active_class'] = 'set-targets';
        $data['rms'] = User::userRole('rm')->get();

        return view('targets.rm', $data);
    }

    public function counselor()
    {
        $data['active_class'] = 'set-targets';
        $data['counselors'] = User::userRole('counselor')->get();
        $data['previous'] = url()->previous();

        return view('targets.counselor', $data);
    }

    public function setTarget($user_id)
    {
        $data['previous'] = URL::to('/target/counselor');
        $data['active_class'] = 'set-targets';
        $data['records'] = Target::where('user_id', $user_id)->orderBy('month_year','DESC')->get();
        $data['user'] = User::find($user_id);

        return view('targets.set_targets', $data);
    }

    public function storeDepartmentTarget(Request $request)
    {
        DepartmentTarget::updateOrCreate(
            [
                'department' => $request->department,
                'month' => $request->month . '-01'
            ],
            [
                'department' => $request->department,
                'month' => $request->month . '-01',
                'target' => $request->target
            ]);

        return redirect()->back();
    }

    public function storeTarget(Request $request, $user_id)
    {
        $data['user_id'] = $user_id;
        $data['target'] = $request->target;
        $data['month_year'] = $request->month_year . '-01';

        Target::updateOrCreate(
            ['user_id' => $data['user_id'], 'month_year' => $data['month_year']],
            ['target' => $request->target]
        );

        return redirect()->back();
    }
}
