@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'Assign Rms')

@section('header_scripts')

<style type="text/css">
	
	.btn-black {
		background-color: black;
		color: white;
	}

	.btn-orange {
		background-color: #FF7D28;
		color: black;
	}

	.btn-yellow {
		background-color: #FFD828;
		color: black;
	}

	.btn-majenta {
		background-color: #FF384A;
		color: black;
	}
</style>

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
					<a href="{{ route('sms.index', $client->id) }}" class="btn btn-black btn-block button2">Send SMS</a>
				</div>

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('email.index', $client->id) }}" class="btn btn-default btn-block button2">Send Email</a>
				</div>

				@if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'backend')

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('client.counsellor', $client->id) }}" class="btn btn-primary btn-block button2">Assign Counselor</a>
				</div>


				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('client.rm', $client->id) }}" class="btn btn-success btn-block button2">Assign RM</a>
				</div>

				@endif

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('appointment.client', $client->id) }}" class="btn btn-yellow btn-block button2">Set Appointment</a>
				</div>

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px">
					<a href="{{ route('client.myprograms', $client->id) }}" class="btn btn-orange btn-block button2">Client Programs and Tasks</a>
				</div>

				@if($client->hasSpouse)

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px;">
					<a href="{{ route('spouse.myprograms', $client->id) }}" class="btn btn-danger btn-block button2">Spouse Programs and Tasks</a>
				</div>

				@endif

				<div class="col-md-offset-2 col-md-8" style="margin-top: 20px; margin-bottom: 20px">
					<a href="{{ route('payment.notes', $client->id) }}" class="btn btn-majenta btn-block button2">Payment Notes</a>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection