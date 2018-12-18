@extends('layouts.master')

@section('url', '/client/action/' . $user->id)

@section('title', 'Set Appointment')

@section('header_scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>

@endsection

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-body">
      	<span>
      		<h2>:: Set Appointment
      			<span class="pull-right">
      				<a href="{{ url('https://calendar.google.com/calendar/r/month') }}" class="btn btn-success button4"><i class="fa fa-calendar"></i> View Google Calendar</a>
      			<a href="{{ route('oauthCallback') }}" class="btn btn-danger button4"><i class="fa fa-google"></i> Sign In</a>
      			</span>
      			
      		</h2>
      	</span>
      	
      	<hr>
      	{{ Form::open(['route' => 'gcalendar.store', 'autocomplete' => 'off']) }}
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
		         	{!! Form::label('client_email', 'Client Email: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::text('client_email', $user->email, ['class'=>'form-control', 'placeholder'=>'Client Email', /*'required'*/]) !!}
		            </div>
		         </div>
		      </div>

		      

		      {{-- <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('appointee', 'Select RM / Counsellor: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {!! Form::select('appointee', $rms_counsellors->pluck('name', 'id'), null, ['class'=>'form-control']) !!}
		            </div>
		         </div>
		      </div> --}}

		      

		      {{ Form::hidden('appointee', Auth::user()->id) }}

		      

		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('start_date', 'Start Date: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {{-- {!! Form::date('start_date', null, ['class'=>'form-control', /*'required'*/]) !!} --}}
		               <input type="text" name="start_date" id="datepicker" class="form-control">
		            </div>
		         </div>
		      </div>
		      <div class="col-md-6">
		         <div class="field-spacing">
		         	{!! Form::label('start_time', 'Start Time: ') !!}
		            <div class="input-group">
		               <span class="input-group-addon"><i class="fa fa-user"></i></span>
		               {{-- {!! Form::input('time', 'starttime', date('00:00'), ['class' => 'form-control']) !!} --}}
		               <input type="text" name="starttime" class="form-control timepicker">
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

@section('footer_scripts')

<script type="text/javascript">

	$( function() {
	    $("#datepicker").datepicker({
	    beforeShowDay: function(date) {
	        var day = date.getDay();
	        return [(day != 5), ''];
	    }
	});
  });


	$(document).ready(function() {
		$('.select2').select2();
	});

	$('.timepicker').timepicker({
	    timeFormat: 'h:mm p',
	    interval: 30,
	    minTime: '10:30am',
	    maxTime: '6:30pm',
	    defaultTime: '10:30',
	    startTime: '10:30',
	    dynamic: false,
	    dropdown: true,
	    scrollbar: true
	});

</script>

@endsection