@extends('layouts.master')
@section('title', 'Users')
@section('header_scripts')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready( function () {
       $('#users').DataTable({
       	'columnDefs' : [
       		{
       			'searchable' : false,
       			'targets' : [4,5]
       		}
       	]
       });
   } );
</script>
@stop
@section('content')
<div class="container-fluid">
   <button type="button" class="btn btn-info button3 btn-block" data-toggle="modal" data-target="#addUser" style="margin-bottom: 20px;">Add User</button>
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
                     '1' => 'Admin', 
                     '2' => 'Rm',
                     '3' => 'Accountant',
                     '4' => 'Backend',
                     '5' => 'Counsellor',
                     '6' => 'Client',
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
   <div id="addUser" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Register User:</h4>
            </div>
            <div class="modal-body">
            	<div class="container-fluid">
            		{{ Form::open(['route'=>'staff.store']) }}
        			<div class="form-group">
        				{!! Form::label('name', 'Name: ') !!}
                        {!! Form::text('name', null, ['class'=>'form-control']) !!}
        			</div>

        			<div class="form-group">
        				{!! Form::label('email', 'Email: ') !!}
                        {!! Form::email('email', null, ['class'=>'form-control']) !!}
        			</div>

        			<div class="form-group">
        				{!! Form::label('user_role', 'User Role: ') !!}
                        {!! Form::select('user_role', [
                        	'RM' => 'RM',
                        	'counsellor' => 'Counsellor',
                        	'accountant' => 'Accountant',
                        	'backend' => 'Backend',
                        	'operation' => 'Operation',
                        	'backend' => 'Backend',
                        	], null, ['class'=>'form-control']) !!}
        			</div>

        			<div class="form-group">
        				{!! Form::label('password', 'Set password: ') !!}
                        {!! Form::password('password', null, ['class'=>'form-control']) !!}
        			</div>
            	</div>
            </div>
            <div class="modal-footer">
            	<div class="form-group">
                    {!! Form::submit('Submit', ['class'=>'btn btn-primary btn-block button3']) !!}
    			</div>
    			{{ Form::close() }}
               <button type="button" class="btn btn-success btn-block button3" data-dismiss="modal">Close</button>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection