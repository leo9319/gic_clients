<?php

namespace App\Http\Controllers;

use App\File;
use App\Program;
use App\VisaType;
use App\Education;
use App\University;
use App\Profession;
use App\Field;
use App\knowledge;
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
        $data['programs'] = Program::all();
        $data['visa_types'] = VisaType::all();
        $data['education_levels'] = Education::all();
        $data['universities'] = University::all();
        $data['professions'] = Profession::all();
        $data['fields'] = Field::all();
        $data['knowledge'] = knowledge::all();

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

        $programs = $request->input('programs');
        $visa_types = $request->input('visa_type');
        $education_levels = $request->input('education_levels');
        $professions = $request->input('professions');
        $knowledge = $request->input('hear_about_us');

        $jsonProgram = json_encode($programs);
        $jsonVisaType = json_encode($visa_types);
        $jsonEducationLevel = json_encode($education_levels);
        $jsonProfessions = json_encode($professions);
        $jsonKnowledge = json_encode($knowledge);

        $request->merge([
            'user_id' => $user->id,
            'programs' => $jsonProgram,
            'visa_type' => $jsonVisaType,
            'education' => $jsonEducationLevel,
            'profession' => $jsonProfessions,
            'hear_about_us' => $jsonKnowledge,
        ]);

        File::updateOrCreate(['user_id' => $user->id],request()->except(['_token']));

        return view('file.acknowledgement');

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
