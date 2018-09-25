@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'RMs')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-footer">

			<div class="profile-header">

				<div class="profile-main">

					<img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">

					<h3 class="name">{{ $client->name }}</h3>

				</div>

			</div>

		</div>
		
	</div>

   <div class="panel">

      <div class="panel-body" style="width:100%">

      	<table class="table">

		    <thead>
		      <tr>
		        <th>Name</th>
		        <th>Action</th>
		      </tr>
		    </thead>


		    <tbody>
		    	@foreach($rms as $rm)
			      <tr>
			        <td>{{ App\User::find($rm->rm_id)->name }}</td>
			        <td><a href="{{ route('task.client.user', [$rm->client_id, $rm->rm_id]) }}" class="btn btn-info">View Tasks</a></td>
			      </tr>
			    @endforeach
		    </tbody>
		  </table>

      </div>

  </div>

</div>

@endsection