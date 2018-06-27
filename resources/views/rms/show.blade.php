@extends('layouts.master')

@section('title', 'RMs')

@section('content')
<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h3>:: Clients</h3>
		</div>
		<div class="panel-footer">
			<table class="table table-striped">
		    <thead>
		      <tr>
		        <th>Name</th>
		        <th>Profile</th>
		      </tr>
		    </thead>
		    <tbody>
		      @foreach($clients as $client)
		      	@foreach($client->profiles as $profile)
			      <tr>
			        <td>{{ $profile->name }}</td>
			        <td><a href="{{ route('client.profile', $profile->id) }}"><button class="btn btn-sm btn-success button3">View Profile</button></a></td>
			      </tr>
		      	@endforeach
		      @endforeach
		    </tbody>
		  </table>
		</div>
	</div>
</div>
@endsection