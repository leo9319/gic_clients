@extends('layouts.master')

@section('title', 'Clients')

@section('content')
<div class="container-fluid">
	<div class="panel-body">
		<div class="profile-header">
			<div class="overlay"></div>
			<div class="profile-main">
				<img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">
				<h3 class="name">{{ $client->name }}</h3>
			</div>

		</div>
	</div>

	<div class="panel">
		<div class="panel-body">
			<table class="table table-striped">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Program Name</th>
						<th>Individual</th>
						<th>Group Task</th>
						<th>Assign To</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					@foreach($programs as $index => $program)
						@foreach($program->programInfo as $program_info)
						<tr>
							

							<td>{{ $index + 1 }}</td>
							<td>{{ $program_info->program_name }}</td>
							<td>
								<a href="{{ route('store.client.task', ['program_id' => $program_info->id, 'client_id' => $client->id ]) }}">
									<button class="btn btn-info button4">Assign Individual Task</button>
								</a>
							</td>
							{{ Form::open(['route'=>['task.group.store', $client->id, $program_info->id]]) }}
							<td>
								{{ Form::select('program_group_id', $listed_programs->pluck('program_name', 'id'), isset($program_group_id[$program_info->id]) ? $program_group_id[$program_info->id] : null, ['class'=>'form-control']) }}
							</td>
							<td>
								{{ Form::select('rms', $listed_rms->pluck('name', 'id'), isset($assignee_id[$program_info->id]) ? $assignee_id[$program_info->id] : null, ['class'=>'form-control']) }}
							</td>
							<td>{{ Form::submit('Submit', ['class'=>'btn btn-success button4']) }}</td>

							{{ Form::close() }}
						</tr>
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	
</div>
@endsection