@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'All Staffs')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

$(document).ready( function () {

  $('#users').DataTable({

    'columnDefs' : [

      {

        'searchable' : false,

        'targets' : [5,6,7]

      }

    ]

  });

} );

</script>

@stop

@section('content')

<div class="container-fluid">

{{--   <button type="button" class="btn btn-info button2 btn-block" data-toggle="modal" data-target="#addUser" style="margin-bottom: 20px;">Add User</button> --}}

  <div class="panel panel-headline">

    <div class="panel-body">

      <table id="users" class="table table-striped table-bordered" style="width:100%">

        <thead>

          <tr>

            <th>SL.</th>

            <th>Name</th>

            <th>Mobile</th>

            <th>Email</th>

            <th>User Role</th>

          </tr>

        </thead>

        <tbody>

          @foreach($users as $index=>$user)

          <tr>

            <td>{{ $index + 1 }}</td>

            <td>{{ $user->name }}</td>

            <td>{{ $user->mobile }}</td>

            <td>{{ $user->email }}</td>

            <td>{{ $user->user_role }}</td>

            {{ Form::close() }}

          </tr>
          @endforeach

        </tbody>

        <tfoot>

        <tr>

          <th>SL.</th>

          <th>Name</th>

          <th>Mobile</th>

          <th>Email</th>

          <th>User Role</th>

        </tr>

        </tfoot>

      </table>

    </div>

  </div>

  <!-- Add User Modal -->

{{--   <div id="addUser" class="modal fade" role="dialog">

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

              {!! Form::label('mobile', 'Mobile: ') !!}
              {!! Form::number('mobile', null, ['class'=>'form-control']) !!}

            </div>

            <div class="form-group">

              {!! Form::label('email', 'Email: ') !!}
              {!! Form::email('email', null, ['class'=>'form-control']) !!}

            </div>

            <div class="form-group">

              {!! Form::label('user_role', 'User Role: ') !!}
              {!! Form::select('user_role', $user_roles, null, ['class'=>'form-control']) !!}

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

  </div> --}}

</div>

</div>

@endsection