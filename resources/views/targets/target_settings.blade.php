@extends('layouts.master')

@section('title', 'Department Targets')

@section('header_scripts')

@stop

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h3>Target Settings</h3>

		</div>

		@if(session()->has('success'))

		<div class="alert alert-success text-center">

	  		<strong>{{ session()->get('success') }}</strong>

		</div>

		@endif


		{{ Form::open(['route' => 'target.setting.store']) }}


		@foreach($programs as $program)

			<div class="panel-footer">

				<h4><b>{{ $program->program_name }}:</b></h4>
			
		        <table id="rms" class="table table-striped">

		            <thead>

		               <tr>

		                  <th>Step Name</th>

		                  <th>RM Count</th>

		                  <th>Counselor Count</th>

		               </tr>

		            </thead>

		            <tbody>

		            	@foreach($program->steps as $step)

		                  <tr>

							<td>{{ $step->step_name }}</td>

							<td>
								<input type="number" name="{{ $step->id }}[rm_count]" class="form-control" value="{{ $step->target->rm_count ?? 0 }}" step="any" min="0" style="max-width: 100px">
							</td>
							<td>
								<input type="number" name="{{ $step->id }}[counselor_count]" class="form-control" value="{{ $step->target->counselor_count ?? 0 }}" step="any" min="0" style="max-width: 100px">
							</td>

		                  </tr>

		              	@endforeach

		            </tbody>

		        </table>

	 		</div>

		@endforeach

		<div class="panel-body" style="margin: 25px 0px 25px 0px">

			<button type="submit" class="btn btn-success btn-block btn-lg button2">Save Settings</button>

		</div>


		{{ Form::close() }}


	</div>

</div>


@endsection