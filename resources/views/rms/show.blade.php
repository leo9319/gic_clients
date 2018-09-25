@extends('layouts.master')

@section('url', '/rms')

@section('title', 'RMs')

@section('header_scripts')
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#rm_clients').DataTable({
		    	'columnDefs' : [
		    		{
		    			'searchable' : false,
		    			'targets' : 4
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
			<h3>:: Clients</h3>
		</div>
		<div class="panel-footer">
			<table id="rm_clients" class="table table-striped" style="width:100%">
	            <thead>
	               <tr>
	                  <th>Client ID.</th>
	                  <th>Name</th>
	                  <th>Phone</th>
	                  <th>Email</th>
	                  <th>Action</th>
	               </tr>
	            </thead>
	            <tbody>
	               @foreach($clients as $client)
	               	@foreach($client->clients as $profile)
		               <tr>
		                  <td><a href="{{ route('client.profile', ['client_id'=> $profile->id ]) }}">{{ $profile->client_code }}</a></td>
		                  <td>{{ $profile->name }}</td>
		                  <td>{{ $profile->mobile }}</td>
		                  <td>{{ $profile->email }}</td>
		                  <td>
		                     <a href="{{ route('client.action', $profile->id) }}" class="btn btn-outline-warning button2">View Actions</a>
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