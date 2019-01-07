<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RmClient;
use App\User;

class RmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->only('index');
    }

    public function index()
    {
        $data['previous'] = url()->previous();
        $data['active_class'] = 'assigend_clients';
        $data['rms'] = User::where('user_role', 'rm')->get();

        return view('rms.index', $data);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['active_class'] = 'assigend_clients';
        $data['clients'] = RmClient::where('rm_id', $id)->get();

        return view('rms.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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

    public function myPrograms($rm_id)
    {
        $data['active_class'] = 'rm-tasks';
        // $data['rm'] = User::where('id', $rm_id)->first();
        $data['rm'] = User::find($rm_id);
        $data['programs'] = ClientProgram::programs($client_id);
        $data['all_programs'] = Program::all();

        return view('clients.myprograms', $data);
    }

    public function removeClient($rm_client_id)
    {
        RmClient::find($rm_client_id)->delete();

        return redirect()->back();
    }

}
