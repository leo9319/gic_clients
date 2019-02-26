@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'Programs')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-footer">

			<div class="profile-header">

				<div class="profile-main">

					<img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">

					<a href="{{ url('client/action/' . $client->id) }}"><h3 class="name">{{ $client->name }}</h3></a>

				</div>

			</div>

		</div>
		
	</div>

	
	<div class="panel">

		<div class="panel-heading">

         <h3 class="panel-title">Program Lists</h3>

         @if(Auth::user()->user_role != 'client' && $client->status == 'active')

           <div class="right">


              <a href="#" class="btn btn-success button2" data-toggle="modal" data-target="#addProgram">Add Program</a>



           </div>

         @endif

      </div>

		<div class="panel-body">

			<table class="table table-striped">

				<thead>

					<tr>

						<th>SL.</th>

						<th>Program Name</th>

						<th class="text-center">Action</th>

						<th class="text-center">Action</th>

					</tr>

				</thead>

				<tbody>

					@foreach($programs as $index => $program)
						@foreach($program->programInfo as $program_info)

						<tr>
							<td>{{ $index + 1 }}</td>

							<td>{{ $program_info->program_name }}</td>

							<td>
								<a href="{{ route('client.steps', ['program_id' => $program_info->id, 'client_id'=>$programs->first()->client_id]) }}">
									<button class="btn btn-info btn-block button2">Steps</button>
								</a>
							</td>

							<td>
								<a href="#">
									<button class="btn btn-danger btn-block button2" id="remove" name="{{ $program->id }}">Remove</button>
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

<div id="addProgram" class="modal fade" role="dialog">

	<div class="modal-dialog">

		<div class="modal-content">

			<div class="modal-header">

				<button type="button" class="close" data-dismiss="modal">&times;</button>

				<h4 class="modal-title">Add Program</h4>

			</div>

			<div class="modal-body">

				{!! Form::open(['route'=>['client.myprograms.store', $client->id]]) !!}

					<div class="form-group">

						{{ Form::label('Add Program:') }}

						{{ Form::select('program_id', $all_programs->pluck('program_name', 'id'), null, ['class'=>'form-control']) }}
						
					</div>

			</div>

			<div class="modal-footer">

				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

					{{ Form::submit('Add', ['class'=>'btn btn-success']) }}

				{!! Form::close() !!}

			</div>

		</div>

	</div>

</div>

<div class="modal fade" id="remove-program" role="dialog">
  <div class="modal-dialog">

      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Warning!</h4>
          </div>
          <div class="modal-body">
              <p>Are you sure you want to remove the program?</p>
          </div>
          <div class="modal-footer">

          	  {{ Form::open(['route'=>'client.remove_my_program']) }}

          	  	{{ Form::hidden('client_programs_id', null, ['id'=>'client-program-id']) }}

          	  	{{ Form::submit('Yes', ['class'=>'btn btn-danger']) }}

              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>

              {{ Form::close() }}
          </div>
      </div>

    </div>
</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">
	
	$('#remove').on('click', function(){

		$('#client-program-id').val(this.name);

		$('#remove-program').modal();
	});

</script>

@endsection