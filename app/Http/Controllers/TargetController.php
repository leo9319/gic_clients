<?php

namespace App\Http\Controllers;

use App\Target;
use App\User;
use Illuminate\Http\Request;
use Carbon;

class TargetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rm()
    {
        $data['active_class'] = 'set-targets';
        $data['rm_targets'] = Target::currentMonthTarget();

        return view('targets.rm', $data);
    }

    public function counselor()
    {
        $data['active_class'] = 'set-targets';
        $data['counselor_targets'] = Target::currentMonthTarget();

        return view('targets.counselor', $data);
    }

    public function setTarget($user_id)
    {
        $data['active_class'] = 'set-targets';
        $data['records'] = Target::where('user_id', $user_id)->orderBy('month_year','DESC')->get();

        return view('targets.set_targets', $data);
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
