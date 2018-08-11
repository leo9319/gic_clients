@extends('layouts.master')

@section('title', 'Tasks')

@section('content')

<div class="container-fluid">

	<div class="panel panel-headline">

		<div class="panel-body">

			<span class="h1">Programs:</span>

			<a href="#" type="button" class="btn btn-success pull-right button2" data-toggle="modal" data-target="#addProgramModal">Add Program</a>

		</div>

		@foreach($programs as $program)

		<div class="panel-footer">

			<p>

				<span>{{ $program->program_name }}</span>

				<a href="{{ route('step.show', $program->id) }}"><button class="btn btn-primary pull-right button2">
				View Steps</button></a>

			</p>

		</div>

		@endforeach

		<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="exampleModalLabel">Add Program</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

						</button>

					</div>

					<div class="modal-body">

						{!! Form::open(['route'=>'program.store']) !!}

							<div class="form-group">

								{{ Form::label('Program Name:' , null, ['class' => 'control-label']) }}

								{{ Form::text('program_name', null, ['class' => 'form-control']) }}

							</div>

					</div>

					<div class="modal-footer">

						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

						{{ Form::submit('Add', ['class'=>'btn btn-primary']) }}

					</div>

					{!! Form::close() !!}

				</div>

			</div>

		</div>

	</div>

</div>

@endsection