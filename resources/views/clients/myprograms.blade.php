@extends('layouts.master')

@section('title', 'Programs')

@section('content')
<div class="container-fluid">
	<div class="panel-body">
		<div class="profile-header">
			<div class="overlay"></div>
			<div class="profile-main">
				<img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">
				<h3 class="name">{{ $client->name}}</h3>
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
								<a href="{{ route('client.mytasks', ['program_id' => $program_info->id, 'client_id'=>Auth::user()->id]) }}">
									<button class="btn btn-info button4">View Tasks</button>
								</a>
							</td>
						</tr>
						@endforeach
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection