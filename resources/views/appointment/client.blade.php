@extends('layouts.master')

@section('title', 'Set Appointment')

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-body">
      	<h2>:: Set Appointments with RMs</h2>
  		<hr>
      	{{ Form::open(['route' => 'gcalendar.store']) }}
			<div class="row">
		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('title', 'Title: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::text('title', null, ['class'=>'form-control', 'placeholder'=>'Title', /*'required'*/]) !!}
		            </div>
		         </div>
		      </div>
		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('description', 'Description: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::text('description', null, ['class'=>'form-control', 'placeholder'=>'Description', /*'required'*/]) !!}
		            </div>
		         </div>
		      </div>

		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('rm', 'Select RM: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::select('rm', $rms->pluck('name', 'id'), null, ['class'=>'form-control']) !!}
		            </div>
		         </div>
		      </div>

		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('start_date', 'Start Date: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::date('start_date', null, ['class'=>'form-control', /*'required'*/]) !!}
		            </div>
		         </div>
		      </div>
		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('start_time', 'Start Time: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::input('time', 'starttime', date('00:00'), ['class' => 'form-control']) !!}
		            </div>
		         </div>
		      </div>

		      <div class="col-md-12">
		      	<div class="field-spacing">
		      		{!! Form::submit('Create Event', ['class'=>'btn btn-primary btn-block button4']) !!}
		      	</div>
		      </div>
		   </div>
      	{{ Form::close() }}
      </div>
  </div>
</div>
@endsection