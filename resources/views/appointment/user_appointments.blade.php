@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'My Appointments')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-heading">

			<h3 class="panel-title">My Appointments</h3>
			
		</div>

		<div class="panel-body">

			<ul class="list-unstyled todo-list">

				@forelse($appointments as $appointment)

					@forelse($appointment->client as $client_profile)

					<li>

						<div class="row">

							<div class="col-md-6">

								<label class="control-inline fancy-checkbox">

									<i class="fa fa-comments"></i>
									
								</label>

								<p>

									<span class="title">{{ $client_profile->name }}</span>

									<span class="short-description">{{ $appointment->title }}</span>

									<span class="date">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($appointment->app_date . $appointment->app_time))->diffForHumans()  }}</span>

								</p>
								
							</div>

							<div class="col-md-3">
								
								<p>

									<span class="title">Date & Time:<br></span>

									<span class="date">{{ Carbon\Carbon::parse($appointment->app_date)->format('d M, Y') }}<br></span>

									<span class="date">{{ Carbon\Carbon::parse($appointment->app_time)->format('g:i a') }}</span>

								</p>

							</div>

							<div class="col-md-3">
								
								<p>

									<a href="{{ route('comment.appointments', $appointment->id) }}" class="btn btn-info btn-xs button2">Add Comment</a>

								</p>

							</div>
							
						</div>
						
					</li>

					@empty

						<p>You do not have any appointment set up.</p>

					@endforelse

					@empty

				@endforelse

			</ul>

		</div>

	</div>

</div>

@endsection