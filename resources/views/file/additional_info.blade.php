@extends('layouts.master')
@section('title', 'Add File')
@section('content')
<div class="container-fluid">
   <h1 class="text-center" style="border: black 1px; padding-bottom: 20px">:: Do you want to come to Canada as a skilled immigrant? ::</h1>
   <div class="row">
      <div class="col-md-12">
         <div class="panel">
            <div class="panel-body">
               {{ Form::open(['route'=>'store.addition', 'method'=>'POST']) }}
               <input type="button" class="btn btn-info" name="" value="Add Work History" onclick = "workHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px">

               <div id="work-history"></div>

               <input type="button" class="btn btn-info" name="" value="Add Education History" onclick = "educationHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px">

               <div id="education-history"></div>

               <h3 class="sub-header-padding">Email Address:</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                     {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>'example@example.com']) !!}
                  </div>

                  <h3 class="sub-header-padding">Security Question 1:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('question1', null, ['class'=>'form-control', 'placeholder'=>'Security Question 1']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Answer 1:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('answer1', null, ['class'=>'form-control', 'placeholder'=>'Answer 1']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Question 2:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('question2', null, ['class'=>'form-control', 'placeholder'=>'Security Question 2']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Answer 2:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('answer2', null, ['class'=>'form-control', 'placeholder'=>'Answer 2']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Question 3:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('question3', null, ['class'=>'form-control', 'placeholder'=>'Security Question 3']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Answer 3:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('answer3', null, ['class'=>'form-control', 'placeholder'=>'Answer 3']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Question 4:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('question4', null, ['class'=>'form-control', 'placeholder'=>'Security Question 4']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Security Answer 4:</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        {!! Form::text('answer4', null, ['class'=>'form-control', 'placeholder'=>'Answer 4']) !!}
                     </div>
                  </div>

                  <h3 class="sub-header-padding">Gender</h3>
                  <label class="fancy-radio">
                  {{ Form::radio('gender', 'male') }}
                  <span><i></i>Male</span>
                  </label>
                  <label class="fancy-radio">
                  {{ Form::radio('gender', 'female') }}
                  <span><i></i>Female</span>
                  </label>
                  <h3 class="sub-header-padding">Date of Birth</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::date('dob', null, ['class'=>'form-control', 'placeholder'=>'Date of Birth']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Marital Status</h3>
                  <label class="fancy-radio">
                  {{ Form::radio('marital_status', 'married') }}
                  <span><i></i>Married</span>
                  </label>
                  <label class="fancy-radio">
                  {{ Form::radio('marital_status', 'single') }}
                  <span><i></i>Single</span>
                  </label>
                  <h3 class="sub-header-padding">Country of Birth</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('country_ob', null, ['class'=>'form-control', 'placeholder'=>'Country of Birth']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">City of Birth</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('city_ob', null, ['class'=>'form-control', 'placeholder'=>'City of Birth']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Do you have a passport or National ID document?</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('passport_or_nid', null, ['class'=>'form-control', 'placeholder'=>'Passport/NID']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Document/ID type</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('document_id', null, ['class'=>'form-control', 'placeholder'=>'Document ID/Type']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Document Number (exactly as shown on the document)</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('document_number', null, ['class'=>'form-control', 'placeholder'=>'Document Number']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Country/Territory of issue</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('country_of_issue', null, ['class'=>'form-control', 'placeholder'=>'Country of issue']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Issue date</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::date('issue_date', null, ['class'=>'form-control']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Expiry date</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::date('expiry_date', null, ['class'=>'form-control']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Have you applied to Immigration, Refugees and Citizenship Canada before?</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::select('immigration_before', [
	                        'Please select' => 'Please select', 
	                        'Yes' => 'Yes',
	                        'No' => 'No'
	                        ], null, ['class'=>'form-control']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Country of citizenship</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('citizenship', null, ['class'=>'form-control', 'placeholder'=>'Country of citizenship']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Country of residence</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('residence', null, ['class'=>'form-control', 'placeholder'=>'Country of residence']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">How many family members do you have? (This includes you, a spouse or partner, dependant children, and their dependant children.)</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::select('family_members', [
                        '1' => '1', 
                        '2' => '2',
                        '3' => '3', 
                        '4' => '4', 
                        '5' => '5', 
                        '6' => '6', 
                        '7 or more' => '7 or more', 
                        ], null, ['class'=>'form-control']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">Do you have a relative who is a citizen or permanent resident of Canada? (The relative must be 18 or older and living in Canada.)</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::select('relative', [
                        'Please select' => 'Please select', 
                        'Yes' => 'Yes', 
                        'No' => 'No' 
                        ], null, ['class'=>'form-control']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">If yes, how is that person related to you?</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('relation', null, ['class'=>'form-control', 'placeholder'=>'Relation']) !!}
                     </div>
                  </div>
                  <h3 class="sub-header-padding">If yes, which province or territory do they reside</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::text('relative_residence',null, ['class'=>'form-control', 'placeholder'=>'Province or Territory of residences']) !!}
                     </div>
                  </div>
                  
                  <h3 class="sub-header-padding">Primary Language</h3>
                  <div class="field-spacing"">
                     <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                        {!! Form::select('primary_language', [
                        'English' => 'English', 
                        'French' => 'French' 
                        ], null, ['class'=>'form-control']) !!}
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">An applicant can appoint a person with immigration, Refugees and Citizenship Canada (IRCC) on their behalf. Do you want to allow IRCC give their information to na appointed person (such as an immigration consultant, lawyer, friend or family member) to contact IRCC on their behalf? This person can also get detials on their case file, such as the status of their submission or application. (This person is called a "representative.")</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::text('representative', null, ['class'=>'form-control', 'placeholder'=>'Representative']) !!}
                  </div>
               </div>

               <h4>Representative</h4>
               <h3 class="sub-header-padding">Representative's Last Name</h3>

               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::text('rep_last_name', null, ['class'=>'form-control', 'placeholder'=>'Representative\'s Last Name']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Representative's First Name</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::text('rep_first_name', null, ['class'=>'form-control', 'placeholder'=>'Representative\'s First Name']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Representative's Email address</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::email('rep_email', null, ['class'=>'form-control', 'placeholder'=>'example@example.com']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Representative's regulatory body ID number (if known)</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::text('rep_id_number', null, ['class'=>'form-control', 'placeholder'=>'Representative\'s ID number']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Have you received nomination certificate from a province or territory?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::text('nomination', null, ['class'=>'form-control', 'placeholder'=>'Representative']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">4 - Digit NOC code</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::text('noc_code', null, ['class'=>'form-control', 'placeholder'=>'4 - Digit NOC code']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Date you first became qualified to practise this occupation</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::date('noc_start_date', null, ['class'=>'form-control', 'placeholder'=>'4 - Digit NOC code']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Do you have a certificate of qualification from Canadian province or territory?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::select('certificate_of_qualification', [
                        'Please select' => 'Please select', 
                        'Yes' => 'Yes',
                        'No' => 'No' 
                        ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               

            </div>
         </div>

      </div>
   </div>
</div>
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
		var edu = '<div>{!! Form::label("field_of_study[]", "Field of Study") !!}{!! Form::text("field_of_study[]", null, ["class" => "form-control required"]) !!}{!! Form::label("edu_start[]", "Since what year?"); !!}<input type="month" name="edu_start[]" class="form-control" required>{!! Form::label("edu_end[]", "To"); !!}<input type="month" name="edu_end[]" class="form-control" required>{!! Form::label("complete_years[]", "Complete or full academic years"); !!}{!! Form::text("complete_years[]", null, ["class" => "form-control"]) !!}{!! Form::label("full_or_part[]", "Full time or part time"); !!}{!! Form::text("full_or_part[]", null, ["class" => "form-control"]) !!}{!! Form::label("standing[]", "Standing at the end of study period") !!}{!! Form::text("standing[]", null, ["class" => "form-control"]) !!}{!! Form::label("country[]", "Country of study") !!}{!! Form::text("country[]", null, ["class" => "form-control"]) !!}{!! Form::label("city[]", "City or town of study") !!}{!! Form::text("city[]", null, ["class" => "form-control"]) !!}{!! Form::label("school[]", "Name of school or instution") !!}{!! Form::text("school[]", null, ["class" => "form-control"]) !!}{!! Form::label("level[]", "Level of education") !!}{!! Form::text("level[]", null, ["class" => "form-control"]) !!}{!! Form::label("degree_canada[]", "Canadian Degree") !!}{!! Form::text("degree_canada[]", null, ["class" => "form-control"]) !!}<br><a href="javascript:void()" class = "btn btn-danger" id="remove-ed" style="border-radius: 20px">Remove Education</a><input type="button" class="btn btn-info" name="" value="Add Education History" onclick = "educationHistory()" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px"><br><br></div>';

		$('#education-history').append(edu);

		$('#education-history').on('click', '#remove-ed', function(e){
                $(this).parent('div').remove();
            });

	}
</script>

@endsection