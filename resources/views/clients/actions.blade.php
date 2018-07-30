@extends('layouts.master')

@section('title', 'Assign Rms')

@section('header_scripts')

@section('content')

<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h2 class="text-center">{{ $client->name }}</h2>
		</div>
	</div>

	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('client.counsellor', $client->id) }}" class="btn btn-primary btn-block button2">Assign Counselor</a>
				</div>

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('client.rm', $client->id) }}" class="btn btn-success btn-block button2">Assign RM</a>
				</div>

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('appointment.client', $client->id) }}" class="btn btn-danger btn-block button2">Set Appointment</a>
				</div>

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px; margin-bottom: 20px">
					<a href="{{ route('client.myprograms', $client->id) }}" class="btn btn-warning btn-block button2">Assign Task</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection