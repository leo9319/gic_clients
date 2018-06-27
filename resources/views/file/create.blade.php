@extends('layouts.master')

@section('title', 'Add File')

@section('header_scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
   <h1 class="text-center" style="border: black 1px; padding-bottom: 20px">:: Do you want to come to Canada as a skilled immigrant? ::</h1>
   <div class="row">
      <div class="col-md-12">

         <div class="panel">
            <div class="panel-body">
               {{ Form::model($file, ['route' => ['file.store']]) }}
               <h3 class="sub-header-padding">Which province or territory do you plan to live in?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('province', [
                     'Please make a selection' => 'Please make a selection', 
                     'Alberta' => 'Alberta',
                     'British Columbia' => 'British Columbia', 
                     'Manitoba' => 'Manitoba', 
                     'New Brunswick' => 'New Brunswick', 
                     'Newfoundland and Labrador' => 'Newfoundland and Labrador', 
                     'Northwest Territories' => 'Northwest Territories', 
                     'Nova Scotia' => 'Nova Scotia', 
                     'Nunavut' => 'Nunavut',
                     'Ontario' => 'Ontario',
                     'Prince Edward Island' => 'Prince Edward Island',
                     'Quebec' => 'Quebec',
                     'Saskatchewan' => 'Saskatchewan',
                     'Yukon' => 'Yukon',
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">Which language test did you take for your <strong class="text-primary">first</strong> official language?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('language1', [
                     'None' => 'None',
                     'IELTS' => 'IELTS',
                     'CELPIP' => 'CELPIP',
                     'TEF' => 'TEF',
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">What date did you take this test?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::date('date1', null, ['class'=>'form-control', 'placeholder'=>'Date of Birth']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Date of test results</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::date('date_of_test_result1', null, ['class'=>'form-control', 'placeholder'=>'Date of Birth']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Language test result form or certificate number</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('certificate_number', null, ['class'=>'form-control', 'placeholder'=>'Language test certificate number']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Language test PIN</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('test_pin1', null, ['class'=>'form-control', 'placeholder'=>'Language test PIN']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Language test version</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('test_version', null, ['class'=>'form-control', 'placeholder'=>'Language test version']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Find out if you're eligible to apply</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <div class="row">
                        <div class="col-md-3">
                           {!! Form::number('speaking1', null, ['class'=>'form-control', 'placeholder'=>'Speaking', 'step' => 'any']) !!}
                        </div>
                        <div class="col-md-3">
                           {!! Form::number('listening1', null, ['class'=>'form-control', 'placeholder'=>'Listening', 'step' => 'any']) !!}
                        </div>
                        <div class="col-md-3">
                           {!! Form::number('reading1', null, ['class'=>'form-control', 'placeholder'=>'Reading', 'step' => 'any']) !!}
                        </div>
                        <div class="col-md-3">
                           {!! Form::number('writing1', null, ['class'=>'form-control', 'placeholder'=>'Writing', 'step' => 'any']) !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">Which language test did you take for your <strong class="text-info">second</strong> official language?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('language2', [
                     'None' => 'None',
                     'IELTS' => 'IELTS',
                     'CELPIP' => 'CELPIP',
                     'TEF' => 'TEF',
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">What date did you take this test?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::date('date2', null, ['class'=>'form-control', 'placeholder'=>'Date of Birth']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Language test result form or certificate number</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('certificate_number2', null, ['class'=>'form-control', 'placeholder'=>'Language test certificate number']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Date of result</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::date('result_date2', null, ['class'=>'form-control', 'placeholder'=>'Date of test result']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Language test version</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('test_version2', null, ['class'=>'form-control', 'placeholder'=>'Language test version']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">Language test PIN</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('test_pin2', null, ['class'=>'form-control', 'placeholder'=>'Language test PIN']) !!}
                  </div>
               </div>


               <h3 class="sub-header-padding">Find out if you're eligible to apply</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <div class="row">
                        <div class="col-md-3">
                           {!! Form::number('speaking2', null, ['class'=>'form-control', 'placeholder'=>'Speaking', 'step' => 'any']) !!}
                        </div>
                        <div class="col-md-3">
                           {!! Form::number('listening2', null, ['class'=>'form-control', 'placeholder'=>'Listening', 'step' => 'any']) !!}
                        </div>
                        <div class="col-md-3">
                           {!! Form::number('reading2', null, ['class'=>'form-control', 'placeholder'=>'Reading', 'step' => 'any']) !!}
                        </div>
                        <div class="col-md-3">
                           {!! Form::number('writing2', null, ['class'=>'form-control', 'placeholder'=>'Writing', 'step' => 'any']) !!}
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">In the last three years, how many years of skilled work experience do you have in Canada? It must have been full-time (or an equal amount in part time).</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('we_three_years', [
                     'None' => 'None',
                     'Less than one year' => 'Less than one year',
                     'One year or more' => 'One year or more'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
               <h3 class="sub-header-padding">If you do not have any Canadian work experience during this period, please choose "None of the above".</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('skill_type_three_years', [
                     'None' => 'None',
                     'Skill Level A (professional occupations)' => 'Skill Level A (professional occupations)',
                     'Skill Level B (technical occupations and skilled trades)' => 'Skill Level B (technical occupations and skilled trades)',
                     'Skill type 0 (managerial occupations)' => 'Skill type 0 (managerial occupations)',
                     'None of the above' => 'None of the above'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">In the last 10 years, how many years of skilled work experience do you have? It must have been continuous, paid, full-time (or an equal amount in part-time), and in only one occupation.</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('we_ten_years', [
                     'Please make a selection' => 'Please make a selection',
                     'None' => 'None',
                     'Less than two years' => 'Less than two years',
                     'Two or more years' => 'Two or more years',
                     'Four to Five' => 'Four to Five',
                     'Six or more' => 'Six or more',
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               <p>In the last five years, do you have at least two years of experience in one of these types of jobs (skilled trades)?</p>
               <ul>
                  <li>industrial, electrical and construction trades (NOC codes that start in 72)</li>
                  <li>maintenance and equipment operation trades (NOC codes that start in 73)</li>
                  <li>supervisors and technical jobs in natural resources, agriculture and related production (NOC codes that start in 82)</li>
                  <li>processing, manufacturing and utilities supervisors and central control operators (NOC codes that start in 92)</li>
                  <li>chefs and cooks or (NOC codes that start in 632)</li>
                  <li>butchers and bakers (NOC codes that start in 633)</li>
               </ul>

               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('skill_trades', [
                     'Please make a selection' => 'Please make a selection',
                     'None' => 'None',
                     'Less than two years' => 'Less than two years',
                     'Two or more years' => 'Two or more years'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">How much money (in Canadian dollars) will you bring to Canada?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('canadian_dollars', [
                     'Please make a selection' => 'Please make a selection',
                     'Less than 12,475 - not enough settlement funds' => 'Less than 12,475 - not enough settlement funds',
                     '12,475 - 15,330' => '12,475 - 15,330',
                     '15,331 - 19,092' => '15,331 - 19,092',
                     '19,093 - 23,180' => '19,093 - 23,180',
                     '23,181 - 26,291' => '23,181 - 26,291',
                     '26,292 - 29,651' => '26,292 - 29,651',
                     '29,652 - 33,013' => '29,652 - 33,013',
                     '33,014 or more' => '33,014 or more',
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">How many family members do you have?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('family_members', [
                     '1' => '1',
                     '2' => '2',
                     '3' => '3',
                     '4' => '4',
                     '5' => '5',
                     '6' => '6',
                     '7' => '7',
                     'Greater than 7' => 'Greater than 7'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>
            </div>
         </div>

         <div class="panel">
            <div class="panel-body">
               <h3 class="sub-header-padding">Do you have a valid job offer in Canada?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('job_offer', [
                     'Please make a selection' => 'Please make a selection',
                     'Yes' => 'Yes',
                     'No' => 'No'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding">Are you currently working legally in Canada?</h3>
               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('currently_working', [
                     'Please make a selection' => 'Please make a selection',
                     'Yes' => 'Yes',
                     'No' => 'No'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               {{ Form::submit('Next', ['class'=>'btn btn-primary btn-block', 'style'=>'margin-top: 50px; border-radius: 20px;']) }}
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
   $(".select2").select2({
      placeholder: "Select your University", 
      allowClear: true,
      
   });
</script>
@endsection