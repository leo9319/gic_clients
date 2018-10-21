@extends('layouts.master')

@section('url', $previous)

@section('title', 'Counslors')

@section('content')

<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h2>{{ $client->name }}</h2>
		</div>
		<div class="panel-footer">
			{{ Form::open(['route'=>['sms.send', $client->id]]) }}
				<div class="form-group">
					{{ Form::label('subject:') }}
					{{ Form::text('subject', null, ['class'=>'form-control']) }}
				</div>
				{!! Form::textarea('sms', null, ['class'=>'form-control', 'rows' => 5]) !!}
				{{ Form::submit('Send SMS', ['class'=>'btn btn-info button2 btn-block', 'style'=>'margin-top:20px;']) }}
			{!! Form::close() !!}
		</div>
	</div>
</div>

@endsection