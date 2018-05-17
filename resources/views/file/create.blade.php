@extends('layouts.template')
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
               <h3 class="panel-title">GIC Registration Form</h3>
            </div>
            <div class="panel-body">
               {{ Form::open(['route'=>'file.store']) }}
               <h3>Choose your Desired Country & Program:</h3>
               <div class="row">
                  @foreach($programs as $index => $program)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('program[]', $program->id) }}
                     <span style="font-weight: lighter;">{{ $program->program_name }}</span>
                     </label>    
                  </div>
                  @endforeach
               </div>
               <h3>Type of Visa you are interested for *</h3>
               <div class="row">
                  @foreach($visa_types as $index => $visa_type)
                  <div class="col-md-6">
                     <label>
                     {{ Form::checkbox('visa_types[]', $visa_type->id) }}
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
                     <span class="input-group-addon"><i class="fa fa-user"></i></span>
                     {!! Form::label('dob', 'Date of Birth', ['class' => 'small text-muted']) !!}
                     {!! Form::date('dob', null, ['class'=>'form-control', 'placeholder'=>'Date of Birth', 'required']) !!}
                  </div>
               </div>
               <label class="fancy-radio">
               <input name="gender" value="male" type="radio">
               <span><i></i>Male</span>
               </label>
               <label class="fancy-radio">
               <input name="gender" value="female" type="radio">
               <span><i></i>Female</span>
               </label>
               <h3>Education *</h3>
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
               <div class="field-spacing"">
                  <div class="">
                     <span class="input-group-addon"><i class="fa fa-university"></i></span>
                     {!! Form::select('education', $education_levels->pluck('education_level', 'id'), null, ['class'=>'form-control select2']) !!}
                  </div>
               </div>
               {!! Form::submit('Submit', ['class'=>'btn btn-primary']) !!}
               {{ Form::close() }}
               <br><br><br>
               <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  {!! Form::text('total_sales', null, ['class'=>'form-control', 'placeholder'=>'Name', 'required']) !!}
               </div>
               <input type="text" class="form-control" placeholder="text field">
               <br>
               <input type="password" class="form-control" value="asecret">
               <br>
               <textarea class="form-control" placeholder="textarea" rows="4"></textarea>
               <br>
               <select class="form-control">
                  <option value="cheese">Cheese</option>
                  <option value="tomatoes">Tomatoes</option>
                  <option value="mozarella">Mozzarella</option>
                  <option value="mushrooms">Mushrooms</option>
                  <option value="pepperoni">Pepperoni</option>
                  <option value="onions">Onions</option>
               </select>
               <br>
               <label class="fancy-checkbox">
               <input type="checkbox">
               <span>Fancy Checkbox 1</span>
               </label>
               <label class="fancy-checkbox">
               <input type="checkbox">
               <span>Fancy Checkbox 2</span>
               </label>
               <label class="fancy-checkbox">
               <input type="checkbox">
               <span>Fancy Checkbox 3</span>
               </label>
               <br>
               <label class="fancy-radio">
               <input name="gender" value="male" type="radio">
               <span><i></i>Male</span>
               </label>
               <label class="fancy-radio">
               <input name="gender" value="female" type="radio">
               <span><i></i>Female</span>
               </label>
            </div>
         </div>
         <!-- END INPUTS -->
         <!-- INPUT SIZING -->
         <div class="panel">
            <div class="panel-heading">
               <h3 class="panel-title">Input Sizing</h3>
            </div>
            <div class="panel-body">
               <input class="form-control input-lg" placeholder=".input-lg" type="text">
               <br>
               <input class="form-control" placeholder="Default input" type="text">
               <br>
               <input class="form-control input-sm" placeholder=".input-sm" type="text">
               <br>
               <select class="form-control input-lg">
                  <option value="cheese">Cheese</option>
                  <option value="tomatoes">Tomatoes</option>
                  <option value="mozarella">Mozzarella</option>
                  <option value="mushrooms">Mushrooms</option>
                  <option value="pepperoni">Pepperoni</option>
                  <option value="onions">Onions</option>
               </select>
               <br>
               <select class="form-control">
                  <option value="cheese">Cheese</option>
                  <option value="tomatoes">Tomatoes</option>
                  <option value="mozarella">Mozzarella</option>
                  <option value="mushrooms">Mushrooms</option>
                  <option value="pepperoni">Pepperoni</option>
                  <option value="onions">Onions</option>
               </select>
               <br>
               <select class="form-control input-sm">
                  <option value="cheese">Cheese</option>
                  <option value="tomatoes">Tomatoes</option>
                  <option value="mozarella">Mozzarella</option>
                  <option value="mushrooms">Mushrooms</option>
                  <option value="pepperoni">Pepperoni</option>
                  <option value="onions">Onions</option>
               </select>
            </div>
         </div>
         <!-- END INPUT SIZING -->
      </div>
      <div class="col-md-12">
         <!-- PROGRESS BARS -->
         <!-- INPUT GROUPS -->
         <div class="panel">
            <div class="panel-heading">
               <h3 class="panel-title">Input Groups</h3>
            </div>
            <div class="panel-body">
               <div class="input-group">
                  <input class="form-control" type="text">
                  <span class="input-group-btn"><button class="btn btn-primary" type="button">Go!</button></span>
               </div>
               <br>
               <div class="input-group">
                  <span class="input-group-btn">
                  <button class="btn btn-primary" type="button">Go!</button>
                  </span>
                  <input class="form-control" type="text">
               </div>
               <br>
               <div class="input-group">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
                  <input class="form-control" placeholder="Username" type="text">
               </div>
               <br>
               <div class="input-group">
                  <input class="form-control" placeholder="Username" type="text">
                  <span class="input-group-addon"><i class="fa fa-user"></i></span>
               </div>
               <br>
               <div class="input-group">
                  <span class="input-group-addon">$</span>
                  <input class="form-control" type="text">
                  <span class="input-group-addon">.00</span>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
   $(".select2").select2({
   placeholder: 'Select a value', 
   allowClear: true
   });
</script>
@endsection