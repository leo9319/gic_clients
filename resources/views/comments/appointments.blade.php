@extends('layouts.master')

@section('title', 'Appointment Comments')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>{{ $client_appointment->title }}</h2>

			<span class="date">Appointment with: {{ App\User::find($client_appointment->appointer_id)->name }}</span>

		</div>

		<div class="panel-footer">

			<div class="container-fluid" style="margin: 20px">

				<ul class="list-unstyled activity-timeline">

					@foreach($comments as $comment)

					<li>

						<i class="fa fa-comment activity-icon"></i>

						<p>{{ App\User::find($comment->commenter_id)->name }}</p>

						<p>

							<q>{{ $comment->comment }}</q>

							<span class="timestamp">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->created_at))->diffForHumans() }}</span>

						</p>

					</li>

					@endforeach

				</ul>

			</div>

		</div>

	</div>


	<div class="panel">

		<div class="panel-body">

			<h3>Add Comment</h3>

		</div>

		<div class="panel-footer">

			{!! Form::open(['route'=>['comment.appointment.store', $client_appointment->id]]) !!}

				{!! Form::textarea('comment', null, ['class'=>'form-control', 'rows' => 5]) !!}
				{!! Form::hidden('client_appointment_id', $client_appointment->title) !!}

				{{ Form::submit('Add Comment', ['class'=>'btn btn-info button2 btn-block', 'style'=>'margin-top:20px;']) }}

			{!! Form::close() !!}
		</div>

	</div>

</div>

@endsection