@extends('layouts.master')

@section('title', 'Clients')

@section('content')
<div class="container-fluid">
	<div class="panel panel-headline">
		<div class="panel-body">
			<h1>Task Groups:</h1>
		</div>
		@foreach($programs as $program)
		<div class="panel-footer">
			<p>
				<span>{{ $program->program_name }}</span>
				<a href="{{ route('task.group', $program->id) }}"><button class="btn btn-primary pull-right button2">Add Tasks</button></a>
			</p>
		</div>
		@endforeach
	</div>
</div>
@endsection