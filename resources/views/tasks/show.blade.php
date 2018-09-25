@extends('layouts.master')

@section('url', '/step/' . $step->program_id)

@section('title', 'Tasks')

@section('content')

<div class="container-fluid">

	<div class="panel panel-headline">

		<div class="panel-body">

			<span class="h1">{{ $step->step_name }}</span><button type="button" class="btn btn-success button2 pull-right" data-toggle="modal" data-target="#addProgramModal">Add Task</button>

		</div>

		@forelse($tasks as $task)

		<div class="panel-footer">

			<p>

				<span>{{ $task->task_name }}</span>

				<span class="pull-right">

					@if($task->file_upload)

						<span class="label label-primary">FILE UPLOAD</span>

					@endif


					@if($task->form_name != 'None Selected')

						<span class="label label-info">FORM: {{ $task->form_name }}</span>

					@endif


					@if($task->duration)

						<span class="label label-danger">{{ $task->duration }} day(s)</span>

					@endif
					

					@if($task->assigned_to)

						|

						<span class="label label-warning">{{ ucfirst($task->assigned_to) }}</span>

					@endif

						||  

					<a href="#" id="{{ $task->id }}" name="{{ $task->task_name }}" onclick="editTask(this)"><span class="label label-default">Edit</span></a>
					<a href="{{ route('delete.task', $task->id) }}"><span class="label label-danger">Delete</span></a>

				</span>

			</p>

		</div>

		@empty

		<div class="panel-footer">

			<p>

				<span>There are currently no tasks!</span>

			</p>

		</div>

		@endforelse

		<div class="modal fade" id="addProgramModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="exampleModalLabel">Add Task</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

						</button>

					</div>

					<div class="modal-body">

						{!! Form::open(['route'=>'task.store']) !!}

						<div class="form-group">

							{{ Form::label('Task Name:' , null, ['class' => 'control-label']) }}
							{{ Form::text('task_name', null, ['class' => 'form-control'], 'required') }}
							{{ Form::hidden('step_id', $step->id) }}

						</div>

						<div class="form-group">

							{{ Form::label('Task For:' , null, ['class' => 'control-label']) }}
							{{ Form::select('assigned_to', [
									'client' => 'Client',
									'spouse' => 'Spouse',
									'counselor' => 'Counselor',
									'rm' => 'RM',
								], null, ['class'=>'form-control']) 
							}}

						</div>

						<div class="form-group">

							{{ Form::label('Task Types:' , null, ['class' => 'control-label']) }}

							<table class="table table-striped">

								<tbody>

									<tr><td>File Upload</td>

										<td>{{ Form::checkbox('file_upload', '1') }}</td>

									</tr>

									<tr>

										<td>With deadline</td>

										<td>

											{{ Form::number('duration', null, ['placeholder' => 'Number of days', 'class' => 'form-control']) }}

										</td>

									</tr>

									<tr>

										<td>Form Fillup</td>

										<td>{{ Form::select('form_name', ['None Selected' => 'None Selected', 'S' => 'Need to change'], null, ['class'=>'form-control']) }}</td>

									</tr>

								</tbody>

							</table>

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

		<div class="modal fade" id="editTask" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

			<div class="modal-dialog" role="document">

				<div class="modal-content">

					<div class="modal-header">

						<h5 class="modal-title" id="exampleModalLabel">Edit Task</h5>

						<button type="button" class="close" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

						</button>

					</div>

					<div class="modal-body">

						{!! Form::open(['route'=>'edit.task']) !!}

						<div class="form-group">

							{{ Form::label('Task Name:' , null, ['class' => 'control-label']) }}
							{{ Form::text('task_name', null, ['class' => 'form-control', 'id'=>'task-name'], 'required') }}
							{{ Form::hidden('task_id', null, ['class' => 'form-control', 'id' => 'task-id']) }}

						</div>

						<div class="form-group">

							{{ Form::label('Task For:' , null, ['class' => 'control-label']) }}
							{{ Form::select('assigned_to', [
									'client' => 'Client',
									'spouse' => 'Spouse',
									'counselor' => 'Counselor',
									'rm' => 'RM',
								], null, ['class'=>'form-control']) 
							}}

						</div>

						<div class="form-group">

							{{ Form::label('Task Types:' , null, ['class' => 'control-label']) }}

							<table class="table table-striped">

								<tbody>

									<tr><td>File Upload</td>

										<td>{{ Form::checkbox('file_upload', '1') }}</td>

									</tr>

									<tr>

										<td>With deadline</td>

										<td>

											{{ Form::text('duration', null, ['placeholder' => 'Number of days', 'class' => 'form-control']) }}

										</td>

									</tr>

									<tr>

										<td>Form Fillup</td>

										<td>{{ Form::select('form_name', ['None Selected' => 'None Selected', 'S' => 'Need to change'], null, ['class'=>'form-control']) }}</td>

									</tr>

								</tbody>

							</table>

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
	
	function editTask(elem) {

		document.getElementById('task-name').value = elem.name;
		document.getElementById('task-id').value = elem.id;

		$("#editTask").modal();

	}

</script>

@endsection

@endsection