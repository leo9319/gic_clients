@extends('layouts.master')

@section('title', 'Set Appointment')

@section('content')
<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h2 class="text-center">Set Appointments</h2>
			<div class="custom-tabs-line tabs-line-bottom center-aligned ">
				<ul class="nav" role="tablist">
					<li class="active"><a href="#tab-bottom-left1" role="tab" data-toggle="tab">With RMS</a></li>
					<li><a href="#tab-bottom-left2" role="tab" data-toggle="tab">With Counsellors </a></li>
				</ul>
			</div>
			<hr>



			<div class="tab-content">
				<!-- with rm -->
				<div class="tab-pane fade in active" id="tab-bottom-left1">

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
								{!! Form::label('Select your email', 'Your Email Address: ') !!}
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									{!! Form::text('client_email', Auth::user()->email, ['class'=>'form-control', 'placeholder'=>'Description', /*'required'*/]) !!}
								</div>
							</div>
						</div>



						<div class="col-md-6">
							<div class="field-spacing">
								{!! Form::label('appointee', 'Select RM: ') !!}
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									{!! Form::select('appointee', $rms->pluck('name', 'id'), null, ['class'=>'form-control']) !!}
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
				<!-- end of with rm -->

				<!-- start off councilors -->
				<div class="tab-pane fade" id="tab-bottom-left2">

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
								{!! Form::label('Select your email', 'Your Email Address: ') !!}
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									{!! Form::text('client_email', Auth::user()->email, ['class'=>'form-control', 'placeholder'=>'Description', /*'required'*/]) !!}
								</div>
							</div>
						</div>



						<div class="col-md-6">
							<div class="field-spacing">
								{!! Form::label('appointee', 'Select Counsellor: ') !!}
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									{!! Form::select('appointee', $counsellors->pluck('name', 'id'), null, ['class'=>'form-control']) !!}
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
				<!-- end of councilors -->
			</div>


		</div>
	</div>
</div>
@endsection