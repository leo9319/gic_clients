@extends('layouts.master')

@section('title', 'Send Email')

@section('content')

<div class="container-fluid">
	
	<div class="panel">

		<div class="panel-body">

			<h2>{{ $client->name }}</h2>

		</div>

		<div class="panel-footer">

			{{ Form::open(['route'=>['email.send', $client->id]]) }}

				<div class="form-group">

					{{ Form::label('subject:') }}

					{{ Form::text('subject', null, ['class'=>'form-control']) }}

				</div>

				{!! Form::textarea('email', null, ['class'=>'form-control', 'rows' => 5]) !!}

				{{ Form::submit('Send Email', ['class'=>'btn btn-info button2 btn-block', 'style'=>'
				margin-top:20px;']) }}

			{!! Form::close() !!}

		</div>

	</div>

</div>


@endsection