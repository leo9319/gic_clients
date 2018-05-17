<?php

namespace App\Http\Controllers;

use App\File;
use App\Program;
use App\VisaType;
use App\Education;
use Auth;
use Illuminate\Http\Request;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::all();
        $data['active_class'] = 'file';

        return view('file.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['active_class'] = 'file';

        // get all the programs:
        $data['programs'] = Program::all();
        $data['visa_types'] = VisaType::all();
        $data['education_levels'] = Education::all();

        return view('file.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $programs = $request->input('program');
        // $request->user_id = $user->id;

        $jsonProgram = $request->programs = json_encode($programs);

        // if the user has no record in the file table then create a new entry for that user
        $user_file_info = File::userFileInformation($user->id);

        if ($user_file_info) { // if the user has information in file table
            echo 'user exists';
        }
        else {
            $file = new File();

            $file->user_id = $user->id;
            $file->programs = $jsonProgram;

            $file->save();
        }



        // else update the file record using the user_id

        

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }

    public function test()
    {
        $data['user_info'] = File::find(1);
        $data['programs'] = Program::all();
        $data['array'] = $this->stringToIntegerArray($data['user_info']->programs);

        return view('a', $data);

    }

    public function stringToIntegerArray($array)
    {
        $array = preg_replace(array('/^\[/','/\]$/'), '',$array);  
        $pattern = "/\"/"; 
        $replace = ""; 
        $array = preg_replace($pattern,$replace,$array); 
        $array = preg_split("/[,]/",$array);

        return $array;
    }
}
