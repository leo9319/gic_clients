<!DOCTYPE html>
<html>
<head>
   <title>GIC Registration Form</title>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
   <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
   <!-- VENDOR CSS -->
   <link rel="stylesheet" href="{{ asset('vendor/bootstrap/css/bootstrap.min.css') }}">
   <link rel="stylesheet" href="{{ asset('vendor/font-awesome/css/font-awesome.min.css') }}">
   <link rel="stylesheet" href="{{ asset('vendor/linearicons/style.css') }}">
   <link rel="stylesheet" href="{{ asset('vendor/chartist/css/chartist-custom.css') }}">
   <!-- MAIN CSS -->
   <link rel="stylesheet" href="{{ asset('css/main.css') }}">
   <!-- GOOGLE FONTS -->
   <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700" rel="stylesheet">
   <!-- ICONS -->
   <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('img/apple-icon.png') }}">
   <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('img/favicon.png') }}">
   <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
   <style type="text/css">
      body {
         margin: 20px;
      }
   </style>
</head>
<body>
<div class="container-fluid">
   <div class="row">
      <div class="col-md-12">
         <!-- INPUTS -->
         <div class="panel">
            <div class="panel-heading">
               <h1 class="text-center" style="border: black 1px solid; border-radius: 6px;">:: GIC Registration Form ::</h1>
            </div>
            <div class="panel-body">
               {{ Form::open(['route' => 'home.form.store']) }}

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
                           {!! Form::text('first_name', null, ['class'=>'form-control', 'placeholder'=>'First Name', 'required']) !!}
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="field-spacing"">
                        <div class="input-group">
                           <span class="input-group-addon"><i class="fa fa-user"></i></span>
                           {!! Form::text('last_name', null, ['class'=>'form-control', 'placeholder'=>'Last Name', 'required']) !!}
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
                              {!! Form::text('mobile', null, ['class'=>'form-control', 'placeholder'=>'Mobile', 'required']) !!}
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6">
                     <div class="field-spacing"">
                        <div class="field-spacing"">
                           <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
                              {!! Form::email('email', null, ['class'=>'form-control', 'placeholder'=>'Email', 'required']) !!}
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
</body>
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
</html>