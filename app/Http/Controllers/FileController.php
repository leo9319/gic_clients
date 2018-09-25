<?php

namespace App\Http\Controllers;

use App\File;
use App\AddtionalInfo;
use App\Program;
use App\VisaType;
use App\Education;
use App\University;
use App\Profession;
use App\Field;
use App\Knowledge;
use App\ClientTask;
use App\ClientProgram;
use DB;
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
        $data['active_class'] = 'file';
        $client_id = Auth::user()->id;
        $data['completed_tasks'] = ClientTask::getTasks($client_id, 'complete');
        $programs = $data['client_programs'] = ClientProgram::programs($client_id);
        $completion_array = [];

        foreach ($programs as $key => $program) {
            foreach ($program->programInfo as $pi) {
                $completion_array[$pi->program_name] = $this->programProgress($client_id, $pi->id);
            }
        }

        $data['program_progresses'] = $completion_array;

        return view('file.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = Auth::user();

        $data['file'] = File::where('user_id', $user->id)->first();
        $data['active_class'] = 'file';
        $data['programs'] = Program::all();
        $data['visa_types'] = VisaType::all();
        $data['education_levels'] = Education::all();
        $data['universities'] = University::all();
        $data['professions'] = Profession::all();
        $data['fields'] = Field::all();
        $data['knowledge'] = Knowledge::all();

        if($data['file']) {
            $data['programs_array'] = $this->stringToIntegerArray($data['file']->programs);
            $data['visa_array'] = $this->stringToIntegerArray($data['file']->visa_type);
            $data['education_array'] = $this->stringToIntegerArray($data['file']->education);
            $data['profession_array'] = $this->stringToIntegerArray($data['file']->profession);
            $data['knowledge_array'] = $this->stringToIntegerArray($data['file']->hear_about_us);
            return view('file.create', $data);
        }

        else {
            return view('file.create', $data);
        }
        
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
        $data['active_class'] = 'file';

        File::updateOrCreate(
            ['user_id' => $user->id],
            ['province' => $request->province,
            'language1' => $request->language1,
            'date1' => $request->date1,
            'date_of_test_result1' => $request->date_of_test_result1,
            'certificate_number' => $request->certificate_number,
            'test_pin1' => $request->test_pin1,
            'test_version' => $request->test_version,
            'speaking1' => $request->speaking1,
            'listening1' => $request->listening1,
            'reading1' => $request->reading1,
            'writing1' => $request->writing1,
            'language2' => $request->language2,
            'date2' => $request->date2,
            'certificate_number2' => $request->certificate_number2,
            'result_date2' => $request->result_date2,
            'test_version2' => $request->test_version2,
            'test_pin2' => $request->test_pin2,
            'speaking2' => $request->speaking2,
            'listening2' => $request->listening2,
            'reading2' => $request->reading2,
            'writing2' => $request->writing2,
            'we_three_years' => $request->we_three_years,
            'we_ten_years' => $request->we_ten_years,
            'skill_trades' => $request->skill_trades,
            'canadian_dollars' => $request->canadian_dollars,
            'family_members' => $request->family_members,
            'job_offer' => $request->job_offer,
            'currently_working' => $request->currently_working,
            'skill_type_three_years' => $request->skill_type_three_years
            ]
        );

        return view('file.additional_info', $data);
    }

    public function storeAddition(Request $request) 
    {
        $user = Auth::user();
        $data['active_class'] = 'file';

        $work_counter = count($request->job_start);

        DB::table('work_histories')->where('user_id', $user->id)->delete();

        for ($i=0; $i < $work_counter; $i++) { 
            DB::table('work_histories')->insert([
                'user_id' => $user->id,
                'job_start' => $request->job_start[$i] . '-01',
                'job_end' => $request->job_end[$i] . '-01',
                'work_hours' => $request->work_hours[$i],
                'noc' => $request->noc[$i],
                'job_title' => $request->job_title[$i],
                'employer_name' => $request->employer_name[$i],
                'job_in_country' => $request->job_in_country[$i]
            ]);
        }

        $edu_counter = count($request->edu_start);

        DB::table('education_histories')->where('user_id', $user->id)->delete();

        for ($i=0; $i < $edu_counter; $i++) { 
            DB::table('education_histories')->insert([
                'user_id' => $user->id,
                'field_of_study' => $request->field_of_study[$i],
                'edu_start' => $request->edu_start[$i] . '-01',
                'edu_end' => $request->edu_end[$i] . '-01',
                'complete_years' => $request->complete_years[$i],
                'full_or_part' => $request->full_or_part[$i],
                'country' => $request->country[$i],
                'city' => $request->city[$i],
                'school' => $request->school[$i],
                'level' => $request->level[$i],
                'degree_canada' => $request->degree_canada[$i]
            ]);
        }

        AddtionalInfo::updateOrCreate(
            ['user_id' => $user->id],
            ['email' => $request->email,
            'question1' => $request->question1,
            'answer1' => $request->answer1,
            'question2' => $request->question2,
            'answer2' => $request->answer2,
            'question3' => $request->question3,
            'answer3' => $request->answer3,
            'question4' => $request->question4,
            'answer4' => $request->answer4,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'marital_status' => $request->marital_status,
            'country_ob' => $request->country_ob,
            'city_ob' => $request->city_ob,
            'passport_or_nid' => $request->passport_or_nid,
            'document_id' => $request->document_id,
            'document_number' => $request->document_number,
            'country_of_issue' => $request->country_of_issue,
            'issue_date' => $request->issue_date,
            'expiry_date' => $request->expiry_date,
            'immigration_before' => $request->immigration_before,
            'citizenship' => $request->citizenship,
            'residence' => $request->residence,
            'family_members' => $request->family_members,
            'relative' => $request->relative,
            'relation' => $request->relation,
            'relative_residence' => $request->relative_residence,
            'representative' => $request->representative,
            'rep_last_name' => $request->rep_last_name,
            'rep_first_name' => $request->rep_first_name,
            'rep_email' => $request->rep_email,
            'rep_id_number' => $request->rep_id_number,
            'nomination' => $request->nomination,
            'noc_code' => $request->noc_code,
            'noc_start_date' => $request->noc_start_date,
            'certificate_of_qualification' => $request->certificate_of_qualification,
            'primary_language' => $request->primary_language]
        );

        return redirect()->route('thanks');

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

    public function myFile()
    {
        $data['active_class'] = 'file';

        // retrieve all the valies from the file table
        $data['file'] = File::where('user_id', Auth::user()->id)->first();
        $data['file_additional'] = AddtionalInfo::where('user_id', Auth::user()->id)->first();

        if (!$data['file_additional']) {
            abort(500);
        }
        else {
             return view('file.my_file', $data);
        }
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

    public function createOld()
    {
        $user = Auth::user();

        $data['file'] = File::where('user_id', $user->id)->first();
        $data['active_class'] = 'file';
        $data['programs'] = Program::all();
        $data['visa_types'] = VisaType::all();
        $data['education_levels'] = Education::all();
        $data['universities'] = University::all();
        $data['professions'] = Profession::all();
        $data['fields'] = Field::all();
        $data['knowledge'] = Knowledge::all();

        if($data['file']) {
            $data['programs_array'] = $this->stringToIntegerArray($data['file']->programs);
            $data['visa_array'] = $this->stringToIntegerArray($data['file']->visa_type);
            $data['education_array'] = $this->stringToIntegerArray($data['file']->education);
            $data['profession_array'] = $this->stringToIntegerArray($data['file']->profession);
            $data['knowledge_array'] = $this->stringToIntegerArray($data['file']->hear_about_us);
            return view('file.create', $data);
        }

        else {
            return view('file.create_empty', $data);
        }
    
        
    }

    public function storeTest(Request $request)
    {
        echo count($request->job_start);
    }

    public function programProgress($client_id, $program_id)
    {
        $tasks = ClientTask::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
        ])->get();

        $percentage = ($tasks->where('status', 'complete')->count() / $tasks->count()) * 100;

        return $percentage;
    }
}
