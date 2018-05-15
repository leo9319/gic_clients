@extends('layouts.template')

@section('header_scripts')
	<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<script>
		$(document).ready( function () {
		    $('#users').DataTable();
		} );
	</script>
@stop

@section('content')
<div class="container-fluid">
	<div class="panel panel-headline">
		<div class="panel-body">
			<table id="users" class="table table-striped table-bordered" style="width:100%">
			    <thead>
			        <tr>
			            <th>SL.</th>
			            <th>Name</th>
			            <th>Email</th>
			            <th>User Role</th>
			            <th>Assign Role</th>
			            <th>Action</th>
			        </tr>
			    </thead>
			    <tbody>
			    	@foreach($users as $index=>$user)
			        <tr>
			            <td>{{ $index + 1 }}</td>
			            <td>{{ $user->name }}</td>
			            <td>{{ $user->email }}</td>
			            <td>{{ $user->user_role }}</td>
						<td>
							{{ Form::open(['route'=>['users.update.role', $user->id]]) }}
			            		{!! Form::select('user_role_id', [
				            			'1' => 'client', 
				            			'2' => 'admin',
				            			'3' => 'guest'
		            				], null, ['class' => 'form-control']) 
	            				!!}
    			            
    			        </td>
    			        <td>{!! Form::submit('Submit', ['class'=>'btn btn-secondary']) !!}</td>
    			        {{ Form::close() }}
			        </tr>
			        @endforeach
			    </tbody>
			    <tfoot>
			        <tr>
			            <th>SL.</th>
			            <th>Name</th>
			            <th>Email</th>
			            <th>User Role</th>
			            <th>Assign Role</th>
			            <th>Action</th>
			        </tr>
			    </tfoot>
			</table>
		</div>
	</div>
</div>

@endsection