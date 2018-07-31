@extends('layouts.master')
@section('title', 'Set Appointment')
@section('header_scripts')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
@endsection
@section('content')
<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h2>Set appointment with RMs</h2>
			<div class="pull-right">
				<a href="/oauth">Sign In</a>
			</div>
		</div>
		<div class="panel-footer">
			{{ Form::open(['route' => 'gcalendar.store']) }}
			<label>Choose Client:</label>
			<select class="select2 form-control" name="client_email">
				@foreach($clients as $client)
				<option value="{{ $client->email }}">{{ $client->name }}</option>
				@endforeach
			</select>
			<br><br>
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
			<input type="date" class="form-control" placeholder="Title" name="start_date">
			<br>
			{!! Form::input('time', 'starttime', date('00:00'), ['class' => 'form-control']) !!}
			<br>
			<input class="btn btn-primary btn-block" type="submit" name="">
			{{ Form::close() }}
		</div>
	</div>
</div>
@endsection
@section('footer_scripts')
<script type="text/javascript">
	$(document).ready(function() {
		$('.select2').select2();
	});
</script>
@endsection