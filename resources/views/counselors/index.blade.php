@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'Counslors')

@section('header_scripts')
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#counselor').DataTable({
		    	'columnDefs' : [
		    		{
		    			'searchable' : false,
		    			'targets' : 3
		    		}
		    	]
		    });
		} );
	</script>
@stop

@section('content')
<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h3>Counselor with assigned clients</h3>
		</div>
		<div class="panel-footer">
			<table id="counselor" class="table table-striped">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Counselor</th>
						<th>Email</th>
						<th>Assigned Clients</th>
					</tr>
				</thead>
				<tbody>
					@foreach($counselors as $index => $counselor)
					<tr>
						<th>{{ $index + 1  }}</th>
						<th>{{ $counselor->name }}</th>
						<th>{{ $counselor->email }}</th>
						<th><a href="{{ route('counselors.show', $counselor->id) }}"><button class="btn btn-primary button2">View Assigned Clients</button></a></th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection