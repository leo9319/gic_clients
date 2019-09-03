@extends('layouts.master')

@section('title', 'Reports')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Reports</h2>

		</div>

		<div class="panel-footer">

			<div class="container-fluid" style="margin: 20px">

				<button type="button" class="btn btn-success btn-block" data-toggle="modal" data-target="#ourCurrentClients">Our Current Clients</button>

			</div>

		</div>

	</div>

</div>


<!-- Current Clients -->
<div id="ourCurrentClients" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Our Current Clients</h4>
      </div>
      <div class="modal-body">
        {{ Form::open(['route'=>'reports.our_current_clients']) }}

        	<div class="form-group">
        		
        		{{ Form::label('Start Date') }}
            <input type="date" name="start_date" class="form-control" required="">

            {{ Form::label('End Date') }}
        		<input type="date" name="end_date" class="form-control" required="">


        	</div>

        	<div class="form-group">

        		{{ Form::submit('View', ['class' => 'btn btn-success btn-block button2']) }}
        		
        	</div>

        {{ Form::close() }}
      </div>
    </div>

  </div>
</div>

@endsection