@extends('layouts.master')
@section('title', 'Add File')
@section('content')
{{ Form::open(['route'=>'store.test', 'method'=>'POST']) }}
 <input type="button" class="btn btn-info" name="" value="Add Work History" onclick = "workHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px">

<div id="work-history"></div>

<input type="button" class="btn btn-info" name="" value="Add Education History" onclick = "educationHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px">

<div id="education-history"></div>
{!! Form::submit('Submit', ['class'=>'btn btn-primary btn-block', 'style'=>'border-radius: 20px;']) !!}
{{ Form::close() }}
@endsection

@section('footer_scripts')

<script type="text/javascript">
	function workHistory () {

		var emp = '<div>{!! Form::label("job_start", "Since what year?"); !!}<input type="month" name="job_start[]" class="form-control" required>{!! Form::label("job_end", "To"); !!}<input type="month" name="job_end[]" class="form-control" required>{!! Form::label("work_hours", "Work hours per week"); !!}{!! Form::number("work_hours[]", null, ["class" => "form-control"]) !!}{!! Form::label("noc", "4-Digit NOC code"); !!}{!! Form::text("noc[]", null, ["class" => "form-control"]) !!}{!! Form::label("job_title", "Job Title"); !!}{!! Form::text("job_title[]", null, ["class" => "form-control"]) !!}{!! Form::label("employer_name", "Employer/company name"); !!}{!! Form::text("employer_name[]", null, ["class" => "form-control"]) !!}{!! Form::label("job_in_country", "Country/territory"); !!}{!! Form::text("job_in_country[]", null, ["class" => "form-control"]) !!}<br><a href="javascript:void()" class = "btn btn-danger" id="remove-ed" style="border-radius: 20px">Remove Employment</a><input type="button" class="btn btn-info" name="" value="Add Work History" onclick = "workHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px"><br><br></div>';

			$('#work-history').append(emp);	

			$('#work-history').on('click', '#remove-ed', function(e){
                $(this).parent('div').remove();
                y--;
            });

	}

	function educationHistory() {
		var edu = '<div>{!! Form::label("field_of_study[]", "Field of Study") !!}{!! Form::text("field_of_study[]", null, ["class" => "form-control"]) !!}{!! Form::label("edu_start[]", "Since what year?"); !!}<input type="month" name="edu_start[]" class="form-control" required>{!! Form::label("edu_end[]", "To"); !!}<input type="month" name="edu_end[]" class="form-control" required>{!! Form::label("complete_years[]", "Complete or full academic years"); !!}{!! Form::text("work_hours[]", null, ["class" => "form-control"]) !!}{!! Form::label("full_or_part[]", "Full time or part time"); !!}{!! Form::text("full_or_part[]", null, ["class" => "form-control"]) !!}{!! Form::label("standing[]", "Standing at the end of study period") !!}{!! Form::text("standing[]", null, ["class" => "form-control"]) !!}{!! Form::label("country[]", "Country of study") !!}{!! Form::text("country[]", null, ["class" => "form-control"]) !!}{!! Form::label("city[]", "City or town of study") !!}{!! Form::text("city[]", null, ["class" => "form-control"]) !!}{!! Form::label("school[]", "Name of school or instution") !!}{!! Form::text("school[]", null, ["class" => "form-control"]) !!}{!! Form::label("level[]", "Level of education") !!}{!! Form::text("level[]", null, ["class" => "form-control"]) !!}{!! Form::label("degree_canada[]", "Canadian Degree") !!}{!! Form::text("degree_canada[]", null, ["class" => "form-control"]) !!}<br><a href="javascript:void()" class = "btn btn-danger" id="remove-ed" style="border-radius: 20px">Remove Education</a><input type="button" class="btn btn-info" name="" value="Add Education History" onclick = "educationHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px"><br><br></div>';

		$('#education-history').append(edu);

		$('#education-history').on('click', '#remove-ed', function(e){
                $(this).parent('div').remove();
            });

	}
</script>

@endsection