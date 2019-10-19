@extends('layouts.master')

@section('title', 'Steps')

@section('content')

<div class="container-fluid">

	<div class="panel panel-headline">

		<div class="panel-body">

			<span class="h1">{{ $program->program_name }}</span>

			<button type="button" class="btn btn-success button2 pull-right" data-toggle="modal" data-target="#addProgramModal">Add Steps
			</button>

		</div>

		<div class="panel-footer">

			<table class="table table-borderless">

			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Step Name</th>
			      <th scope="col">Step No.</th>
			      <th scope="col">Action</th>
			      <th scope="col">Action</th>
			      <th scope="col">Action</th>
			    </tr>
			  </thead>

			  <tbody>
				@foreach($steps as $index => $step)
			    <tr>
					<td scope="row">{{ $index + 1 }}</td>
					<td>{{ $step->step_name }}</td>
					<td>{{ ucwords(str_replace('_', ' ', $step->step_number))  }}</td>

					<td>
						<a href="{{ route('task.show', $step->id) }}" class="btn btn-primary button2 btn-sm">
							View Tasks
						</a>
					</td>

					<td>
						<button type="button" class="btn btn-secondary button2 btn-sm" id="{{ $step->id }}" onclick="editStep(this)">
							<span class="fa fa-edit fa-lg"></span>
						</button>
					</td>

					<td>
						{{ link_to_route('step.delete', 'Delete', ['step_id' => $step->id], ['class' => 'btn btn-danger button2']) }}
					</td>
			    </tr>
			    @endforeach
			  </tbody>
			</table>

		</div>

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

							<div class="form-group">

								{{ Form::label('step_number:' , null, ['class' => 'control-label']) }}

								{{ Form::select('step_number', $step_number, null, ['class' => 'form-control', 'id' => 'step-number']) }}

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

		var step_id     = elem.id;
		var step_name   = document.getElementById('step-name');
		var step_number = document.getElementById('step-number');

		$.ajax({
			type: 'get',
			url: '{!! URL::to('findStep') !!}',
			data: {'step_id': step_id},
			success: function(data) {

				document.getElementById('step-id').value   = step_id;
				step_name.value                            = data.step_name;
				step_number.value                          = data.step_number;

				$("#editStep").modal();
			},
			error: function(data) {
				alert('ERROR!');
			}
		});

	}

</script>

@endsection

@endsection