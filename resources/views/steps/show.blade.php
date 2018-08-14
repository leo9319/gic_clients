@extends('layouts.master')

@section('title', 'Steps')

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

			 <?php $test =  $step->step_name ?>

			<div class="panel-footer">

				<p>

					<span>{{ $step->order . '. ' . $step->step_name }}</span>

					<span class="pull-right">

						<a href="{{ route('task.show', $step->id) }}">

							<button class="btn btn-primary button2">View Tasks</button>

						</a>

						<button type="button" class="btn btn-secondary" id="{{ $step->id }}" name="{{ $step->step_name }}" onclick="editStep(this)"><span class="fa fa-edit fa-lg"></span></button>

						{{ link_to_route('step.delete', 'Delete', ['step_id' => $step->id], ['class' => 'btn btn-danger']) }}

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

						{!! Form::open(['route'=>['step.update', 18], 'method' => 'PUT']) !!}

							<div class="form-group">

								{{ Form::label('Edit Step:' , null, ['class' => 'control-label']) }}

								{{ Form::text('step_name', null, ['class' => 'form-control', 'id' => 'step-name']) }}

								{{ Form::hidden('step_id', null, ['class' => 'form-control', 'id' => 'step-id']) }}

							</div>

					</div>

					<div class="modal-footer">

						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

						{{ Form::submit('Update', ['class'=>'btn btn-primary']) }}

					</div>

					{!! Form::close() !!}

				</div>

			</div>

		</div>

	</div>

</div>

@section('footer_scripts')

<script type="text/javascript">
	
	function editStep(elem) {

		document.getElementById('step-name').value = elem.name;
		document.getElementById('step-id').value = elem.id;

		$("#editStep").modal();

	}

</script>

@endsection

@endsection