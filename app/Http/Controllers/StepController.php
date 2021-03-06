<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Step;
use App\Program;
use URL;

class StepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('show');
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $order = Step::where('program_id', $request->program_id)->count();
        Step::create([
            'step_name'   => $request->step_name,
            'program_id'  => $request->program_id,
            'step_number' => ++$order,
        ]);

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['active_class'] = 'tasks';
        $data['steps']        = Step::where('program_id', $id)->get();
        $data['program']      = Program::find($id);
        $data['step_number']  = [
                                    'not_defined'                     => 'None',
                                    'file_opening'                    => 'File Opening',
                                    'second_installment'              => '2nd Installment ',
                                    'third_fourth_fifth_installment'  => '3rd, 4th and 5th Installment ',
                                ];

        return view('steps.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo 'test';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Step::find($request->step_id)
            ->update(
                [
                    'step_name'   => $request->step_name,
                    'step_number' => $request->step_number,
                ]
            );

        return redirect()->back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function delete($id)
    {
        Step::find($id)->delete();

        return redirect()->back();
    }

    public function findStep(Request $request)
    {
        return Step::find($request->step_id);
    }
}
