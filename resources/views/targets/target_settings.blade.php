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

							<td>1</td>

							<td>2</td>

		                  </tr>

		              	@endforeach

		            </tbody>

		        </table>

	 		</div>

		@endforeach

	</div>

</div>


@endsection