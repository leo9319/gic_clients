@extends('layouts.master')

@section('title', 'Clients')

@section('content')

<div class="container-fluid">

	<div class="panel panel-headline">

		<div class="panel-body">

			<span class="h1">{{ $program->program_name }}</span>

			<button type="button" class="btn btn-success button2 pull-right" data-toggle="modal" data-target="#addProgramModal">

				Add Steps

			</button>

		</div>

		@foreach($steps as $step)

			<div class="panel-footer">

				<p>

					<span>{{ $step->order . '. ' . $step->step_name }}</span>

					<span class="pull-right">

						<a href="{{ route('task.show', $step->id) }}">

							<button class="btn btn-primary button2">View Tasks</button>

						</a>

						<button type="button" class="btn btn-secondary"><span class="fa fa-edit fa-lg"></span></button>

						<button type="button" class="btn btn-danger"><span class="fa fa-trash fa-lg"></span></button>

					</span>
				</p>
			</div>

		@endforeach

		<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="exampleModalLabel">Add Steps</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

						</button>

					</div>

					<div class="modal-body">

						{!! Form::open(['route'=>'step.store']) !!}

							<div class="form-group">

								{{ Form::label('Step Name:' , null, ['class' => 'control-label']) }}

								{{ Form::text('step_name', null, ['class' => 'form-control']) }}

								{{ Form::hidden('program_id', $program->id, ['class' => 'form-control']) }}

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


		<div class="modal fade" id="editStep" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="exampleModalLabel">Edit Step</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

						</button>

					</div>

					<div class="modal-body">

						{!! Form::open(['route'=>['step.edit', 18]]) !!}

							<div class="form-group">

								{{ Form::label('Edit Step:' , null, ['class' => 'control-label']) }}

								{{ Form::text('step_name', null, ['class' => 'form-control']) }}

								{{ Form::hidden('program_id', $program->id, ['class' => 'form-control']) }}

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