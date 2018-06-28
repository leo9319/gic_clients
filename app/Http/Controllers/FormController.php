<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
use App\RegistrationForm;
use DB;
use Auth;

class FormController extends Controller
{
    public function index()
    {
    	$data['programs'] = Program::all();
        $data['visa_types'] = VisaType::all();
        $data['education_levels'] = Education::all();
        $data['universities'] = University::all();
        $data['professions'] = Profession::all();
        $data['fields'] = Field::all();
        $data['knowledge'] = Knowledge::all();

    	return view('file.registration', $data);
    }

    public function store(Request $request)
    {
        $programs = $request->programs;
        $visa_types = $request->visa_type;

        RegistrationForm::updateOrCreate(
            [
                'mobile' => $request->mobile,
                'email' => $request->email
            ],
            [
                'programs'         => json_encode($request->programs),
                'visa_types'       => json_encode($request->visa_type),
                'first_name'       => $request->first_name,
                'last_name'        => $request->last_name,
                'mobile'           => $request->mobile,
                'email'            => $request->email,
                'dob'              => $request->dob,
                'marital_status'   => $request->marital_status,
                'education'        => json_encode($request->education_levels),
                'university'       => $request->university_attended,
                'profession'       => json_encode($request->professions),
                'experience'       => $request->work_experience,
                'field_of_work'    => $request->field_of_work,
                'hear_about_us'    => json_encode($request->hear_about_us),
                'other_countries'  => $request->foreign_country_visited,
            ]);

        return redirect()->route('thanks');
    }
}
