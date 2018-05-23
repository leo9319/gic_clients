@extends('layouts.master')

@section('title', 'Add File')

@section('header_scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="container-fluid">
   <div class="row">
      <div class="col-md-12">
         <!-- INPUTS -->
         <div class="panel">
            <div class="panel-heading">
               <h1 class="text-center" style="border: black 1px solid; border-radius: 6px;">:: GIC Registration Form ::</h1>
            </div>
            <div class="panel-body">
               {{ Form::open(['route' => ['file.store']]) }}

               <h3 class="sub-header-padding"><i class="fa fa-globe"></i> Choose your Desired Country & Program:</h3>
               <div class="row">
                  @foreach($programs as $index => $program)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('programs[]', $program->id) }}
                     <span style="font-weight: lighter;">{{ $program->program_name }}</span>
                     </label>    
                  </div>
                  @endforeach
               </div>
               <h3 class="sub-header-padding"><i class="fa fa-cc-visa"></i> Type of Visa you are interested for *</h3>
               <div class="row">
                  @foreach($visa_types as $index => $visa_type)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('visa_type[]', $visa_type->id) }}
                     <span style="font-weight: lighter;">{{ $visa_type->visa_type }}</span>
                     </label>    
                  </div>
                  @endforeach
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="field-spacing"">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-user"></i></span>
                           {!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'First Name']) !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="field-spacing"">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-user"></i></span>
                           {!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'Last Name']) !!}
                        </div>
                     </div>
                  </div>
               </div>
               <div class="row">
                  <div class="col-md-6">
                     <div class="field-spacing"">
                        <div class="field-spacing"">
                           <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-phone"></i></span>
                              {!! Form::text('mobile', null, ['class'=>'form-control', 'placeholder'=>'Mobile']) !!}
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="field-spacing"">
                        <div class="field-spacing"">
                           <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                              {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>'Email']) !!}
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-birthday-cake"></i></span>
                     {!! Form::date('dob', null, ['class'=>'form-control', 'placeholder'=>'Date of Birth']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding"><i class="fa fa-venus-mars"></i> Marital Status *</h3>
               <label class="fancy-radio">
               {{ Form::radio('marital_status', 'married') }}
               <span><i></i>Married</span>
               </label>
               <label class="fancy-radio">
               {{ Form::radio('marital_status', 'single') }}
               
               <span><i></i>Single</span>
               </label>

               <h3 class="sub-header-padding"><i class="fa fa-graduation-cap"></i> Education *</h3>
               <div class="row">
                  @foreach($education_levels as $index => $education_level)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('education_levels[]', $education_level->id) }}
                     <span style="font-weight: lighter;">{{ $education_level->education_level }}</span>
                     </label>    
                  </div>
                  @endforeach
               </div>
               <h3><i class="fa fa-university"></i> Select your University *</h3>
               <div class="field-spacing"">
                  <div class="">
                     <span class="input-group-addon"><i class="fa fa-university"></i></span>
                     {!! Form::select('university_attended', $universities->pluck('university_name', 'id'), null, ['class'=>'form-control select2']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding"><i class="fa fa-user-md"></i> Profession *</h3>
               <div class="row">
                  @foreach($professions as $index => $profession)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('professions[]', $profession->id) }}
                     <span style="font-weight: lighter;">{{ $profession->profession_type }}</span>
                     </label>    
                  </div>
                  @endforeach
               </div>

               <h3 class="sub-header-padding"><i class="fa fa-suitcase"></i> Total Job Experience *</h3>

               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                     {!! Form::select('work_experience', [
                           'No Experience' => 'No Experience', 
                           'Less than 1 year' => 'Less than 1 year',
                           '1 year' => '1 year', 
                           '2 years' => '2 year', 
                           '3 years' => '3 year', 
                           '4 years' => '4 year', 
                           '5 years or more' => '5 years or more', 
                        ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding"><i class="fa fa-wrench"></i> Field of Work *</h3>
               <div class="field-spacing"">
                  <div class="">
                     <span class="input-group-addon"><i class="fa fa-suitcase"></i></span>
                     {!! Form::select('field_of_work', $fields->pluck('field_type', 'id'), null, ['class'=>'form-control select2']) !!}
                  </div>
               </div>

               <h3 class="sub-header-padding"><i class="fa fa-facebook-square"></i> How did you hear about GIC *</h3>
               <div class="row">
                  @foreach($knowledge as $index => $source)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('hear_about_us[]', $source->id) }}
                     <span style="font-weight: lighter;">{{ $source->source }}</span>
                     </label>    
                  </div>
                  @endforeach
               </div>

               <h3 class="sub-header-padding"><i class="fa fa-map-marker"></i> Did you visit or work in any country other than Bangladesh?</h3>
               <label class="fancy-radio">
               {{ Form::radio('foreign_country_visited', 1) }}
               <span><i></i>Yes</span>
               </label>
               <label class="fancy-radio">
               {{ Form::radio('foreign_country_visited', 0) }}
               <span><i></i>NO</span>
               </label>

               <br><br>

               {!! Form::submit('Submit', ['class'=>'btn btn-primary btn-block button4']) !!}
               {{ Form::close() }}
               
               
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
      data: [{id: -1,
        text: 'None Selected',
        selected: 'selected',
        search:'',
        hidden:true}]
   });
</script>
@endsection