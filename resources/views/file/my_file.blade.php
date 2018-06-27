@extends('layouts.master')

@section('title', 'My File')

@section('content')
<div class="container-fluid">
	<h1 class="text-center" style="border: black 1px; padding-bottom: 20px">:: My File ::</h1>
	<div class="panel">
		<div class="panel-body">
			<h3 class="sub-header-padding">Which province or territory do you plan to live in?</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->province }}" readonly></div>
		</div>
	</div>

	<div class="panel">
        <div class="panel-body">
           <h3 class="sub-header-padding">Which language test did you take for your <strong class="text-primary">first</strong> official language?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->language1 }}" readonly></div>

           <h3 class="sub-header-padding">What date did you take this test?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->date1 }}" readonly></div>

           <h3 class="sub-header-padding">Date of test results</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->date_of_test_result1 }}" readonly></div>

           <h3 class="sub-header-padding">Language test result form or certificate number</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->certificate_number }}" readonly></div>

           <h3 class="sub-header-padding">Language test PIN</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->test_pin1 }}" readonly></div>

           <h3 class="sub-header-padding">Language test version</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->test_version }}" readonly></div>

           <h3 class="sub-header-padding">Find out if you're eligible to apply</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->speaking1 }}" readonly></div>

           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->listening1 }}" readonly></div>

           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->speaking1 }}" readonly></div>

           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->writing1 }}" readonly></div>

           <h3 class="sub-header-padding">Which language test did you take for your <strong class="text-info">second</strong> official language?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->language2 }}" readonly></div>

           <h3 class="sub-header-padding">What date did you take this test?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->date2 }}" readonly></div>

           <h3 class="sub-header-padding">Language test result form or certificate number</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->certificate_number2 }}" readonly></div>

           <h3 class="sub-header-padding">Date of result</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->result_date2 }}" readonly></div>

           <h3 class="sub-header-padding">Language test version</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->test_version2 }}" readonly></div>

           <h3 class="sub-header-padding">Language test PIN</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->test_pin2 }}" readonly></div>

           <h3 class="sub-header-padding">Find out if you're eligible to apply</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->speaking2 }}" readonly></div>

           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->listening2 }}" readonly></div>

           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->reading2 }}" readonly></div>

           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->writing2 }}" readonly></div>

           <h3 class="sub-header-padding">In the last three years, how many years of skilled work experience do you have in Canada? It must have been full-time (or an equal amount in part time).</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->we_three_years }}" readonly></div>

           <h3 class="sub-header-padding">If you do not have any Canadian work experience during this period, please choose "None of the above".</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->skill_type_three_years }}" readonly></div>

           <h3 class="sub-header-padding">In the last 10 years, how many years of skilled work experience do you have? It must have been continuous, paid, full-time (or an equal amount in part-time), and in only one occupation.</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->we_ten_years }}" readonly></div>

           <p>In the last five years, do you have at least two years of experience in one of these types of jobs (skilled trades)?</p>
               <ul>
                  <li>industrial, electrical and construction trades (NOC codes that start in 72)</li>
                  <li>maintenance and equipment operation trades (NOC codes that start in 73)</li>
                  <li>supervisors and technical jobs in natural resources, agriculture and related production (NOC codes that start in 82)</li>
                  <li>processing, manufacturing and utilities supervisors and central control operators (NOC codes that start in 92)</li>
                  <li>chefs and cooks or (NOC codes that start in 632)</li>
                  <li>butchers and bakers (NOC codes that start in 633)</li>
               </ul>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->skill_trades }}" readonly></div>

           <h3 class="sub-header-padding">How much money (in Canadian dollars) will you bring to Canada?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->canadian_dollars }}" readonly></div>

           <h3 class="sub-header-padding">How many family members do you have?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->family_members }}" readonly></div>

           <h3 class="sub-header-padding">Do you have a valid job offer in Canada?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->job_offer }}" readonly></div>

           <h3 class="sub-header-padding">Are you currently working legally in Canada?</h3>
           <div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file->currently_working }}" readonly></div>

			<h3 class="sub-header-padding">Email Address:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->email }}" readonly></div>

			<h3 class="sub-header-padding">Security Question 1:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->question1 }}" readonly></div>

			<h3 class="sub-header-padding">Security Answer 1:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->answer1 }}" readonly></div>

			<h3 class="sub-header-padding">Security Question 2:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->question2 }}" readonly></div>

			<h3 class="sub-header-padding">Security Answer 2:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->answer2 }}" readonly></div>

			<h3 class="sub-header-padding">Security Question 3:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->question3 }}" readonly></div>

			<h3 class="sub-header-padding">Security Answer 3:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->answer3 }}" readonly></div>

			<h3 class="sub-header-padding">Security Question 4:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->question4 }}" readonly></div>

			<h3 class="sub-header-padding">Security Answer 4:</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->answer4 }}" readonly></div>

			<h3 class="sub-header-padding">Gender</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->gender }}" readonly></div>

			<h3 class="sub-header-padding">Date of Birth</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->dob }}" readonly></div>

			<h3 class="sub-header-padding">Marital Status</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->marital_status }}" readonly></div>

			<h3 class="sub-header-padding">Country of Birth</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->country_ob }}" readonly></div>

			<h3 class="sub-header-padding">City of Birth</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->city_ob }}" readonly></div>

			<h3 class="sub-header-padding">Do you have a passport or National ID document?</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->passport_or_nid }}" readonly></div>

			<h3 class="sub-header-padding">Document/ID type</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->document_id }}" readonly></div>

			<h3 class="sub-header-padding">Document Number (exactly as shown on the document)</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->document_number }}" readonly></div>

			<h3 class="sub-header-padding">Country/Territory of issue</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->country_of_issue }}" readonly></div>

			<h3 class="sub-header-padding">Issue date</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->issue_date }}" readonly></div>

			<h3 class="sub-header-padding">Expiry date</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->expiry_date }}" readonly></div>

			<h3 class="sub-header-padding">Have you applied to Immigration, Refugees and Citizenship Canada before?</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->immigration_before }}" readonly></div>

			<h3 class="sub-header-padding">Country of citizenship</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->citizenship }}" readonly></div>

			<h3 class="sub-header-padding">Country of residence</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->residence }}" readonly></div>

			<h3 class="sub-header-padding">How many family members do you have? (This includes you, a spouse or partner, dependant children, and their dependant children.)</h3> 
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->family_members }}" readonly></div>

			<h3 class="sub-header-padding">Do you have a relative who is a citizen or permanent resident of Canada? (The relative must be 18 or older and living in Canada.)</h3> 
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->relative }}" readonly></div>

			<h3 class="sub-header-padding">If yes, how is that person related to you?</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->relation }}" readonly></div>

			<h3 class="sub-header-padding">If yes, which province or territory do they reside</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->relative_residence }}" readonly></div>

			<h3 class="sub-header-padding">Primary Language</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->primary_language }}" readonly></div>

			<h3 class="sub-header-padding">An applicant can appoint a person with immigration, Refugees and Citizenship Canada (IRCC) on their behalf. Do you want to allow IRCC give their information to na appointed person (such as an immigration consultant, lawyer, friend or family member) to contact IRCC on their behalf? This person can also get detials on their case file, such as the status of their submission or application. (This person is called a "representative.")</h3> 
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->representative }}" readonly></div>

			<h3 class="sub-header-padding">Representative's Last Name</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->rep_last_name }}" readonly></div>

			<h3 class="sub-header-padding">Representative's First Name</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->rep_first_name }}" readonly></div>

			<h3 class="sub-header-padding">Representative's Email address</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->rep_email }}" readonly></div>

			<h3 class="sub-header-padding">Representative's regulatory body ID number (if known)</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->rep_id_number }}" readonly></div>

			<h3 class="sub-header-padding">Have you received nomination certificate from a province or territory?</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->nomination }}" readonly></div>

			<h3 class="sub-header-padding">4 - Digit NOC code</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->noc_code }}" readonly></div>

			<h3 class="sub-header-padding">Date you first became qualified to practise this occupation</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->noc_start_date }}" readonly></div>

			<h3 class="sub-header-padding">Do you have a certificate of qualification from Canadian province or territory?</h3>
			<div class="input-group"><span class="input-group-addon"><i class="fa fa-globe"></i></span><input type="text" class="form-control" value="{{ $file_additional->certificate_of_qualification }}" readonly></div>

       </div>
   </div>
</div>
@endsection