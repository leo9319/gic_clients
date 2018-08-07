<?php

namespace App\Http\Controllers;

use App\Program;
use Illuminate\Http\Request;
use DB;

class ProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        Program::create($request->all());

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        //
    }

    public function clientProgram()
    {
        $data = DB::table('client_programs AS CP')
                ->join('programs AS P', 'P.id', '=', 'CP.program_id')
                ->select('P.program_name AS program_name', DB::raw('COUNT(CP.program_id) AS total'))
                ->groupBy('CP.program_id', 'P.program_name')
                ->get();

        return response()->json($data);
    }

    public function programStep(Request $request)
    {
        $data = DB::table('steps AS S')
                ->select('S.id', 'S.step_name', 'S.order')
                ->where('program_id', $request->program_id)
                ->get();

        return response()->json($data);
    }

    public function individualClientProgram(Request $request)
    {
        $data = DB::table('client_programs AS CP')
                ->join('programs AS P', 'P.id', '=', 'CP.program_id')
                ->select('CP.program_id', 'P.program_name')
                ->where('client_id', 68)
                ->get();

        return response()->json($data);
    }
}
