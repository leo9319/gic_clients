@extends('layouts.master')

@section('title', 'RMs')

@section('header_scripts')
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#rms').DataTable({
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
			<h3>:: List of RMs</h3>

			<table id="rms" class="table table-striped">
				<thead>
					<tr>
						<th>SL.</th>
						<th>Relationship Manager</th>
						<th>Email</th>
						<th>Assigned Clients</th>
					</tr>
				</thead>
				<tbody>
					@foreach($rms as $index => $rm)
					<tr>
						<th>{{ $index + 1  }}</th>
						<th>{{ $rm->name }}</th>
						<th>{{ $rm->email }}</th>
						<th><a href="{{ route('rms.show', $rm->id) }}"><button class="btn btn-primary button4">View Assigned Clients</button></a></th>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection