@extends('layouts.master')

@section('url', '/dashboard')

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

			<h2>Set appointment with RMs</h2>

			<div class="pull-right">

				<a href="{{ route('oauthCallback') }}">Sign In</a>

			</div>

		</div>
		<div class="panel-footer">

			{{ Form::open(['route' => 'gcalendar.store', 'autocomplete'=>'off']) }}

			@if(Auth::user()->user_role != 'client')

				<label>Choose Client:</label>

				<select class="select2 form-control" name="client_email">

				@foreach($clients as $client)

					<option value="{{ $client->email }}">{{ $client->name }}</option>

				@endforeach

			</select>

			<br><br>

			@else

			<input type="hidden" name="client_email" value="{{ Auth::user()->email }}">

			@endif

			<label>Choose RM:</label>

			<select class="select2 form-control" name="appointee">

				@foreach($rms as $rm)

				<option value="{{ $rm->id }}">{{ $rm->name }}</option>

				@endforeach
			</select>
			<br><br>
			<input type="text" class="form-control" placeholder="Title" name="title">
			<br>
			<textarea class="form-control" placeholder="Description" rows="4" name="description"></textarea>
			<br>
			<label>Choose Date:</label>
			<input type="text" name="start_date" id="datepicker" class="form-control">
			<br>
			<label>Choose Time:</label>
			<input type="text" name="starttime" class="form-control timepicker">
			<br>
			<input class="btn btn-primary btn-block" type="submit" name="">
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
