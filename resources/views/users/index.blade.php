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

  <button type="button" class="btn btn-info button2 btn-block" data-toggle="modal" data-target="#addUser" style="margin-bottom: 20px;">Add User</button>

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

            <th>Assign Role</th>

            <th>Action</th>

            <th>Action</th>

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

            <td>

              {{ Form::open(['route'=>['users.update.role', $user->id]]) }}

              {!! Form::select('user_role', $user_roles, null, ['class' => 'form-control'])
              !!}

            </td>

            <td>{!! Form::submit('Submit', ['class'=>'btn btn-secondary']) !!}</td>

            {{ Form::close() }}

            <td>

              <button type="button" class="btn btn-secondary" id="{{ $user->id }}" onclick="editUser(this)"><span class="fa fa-edit fa-xs"></span></button>

              <button type="button" class="btn btn-danger btn-sm" id="{{ $user->id }}" onclick="deleteClient(this)"><span class="fa fa-trash fa-xs"></span></button>

            </td>

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

          <th>Assign Role</th>

          <th>Action</th>

          <th>Action</th>

        </tr>

        </tfoot>

      </table>

    </div>

  </div>

  <!-- Add User Modal -->

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

  </div>

</div>

<!-- Edit User Model -->

<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

        <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        <div class="container-fluid">

            {{ Form::open(['route'=>'staff.update', 'autocomplete' => 'off']) }}

            <div class="form-group">

              {!! Form::label('name', 'Name: ') !!}
              {!! Form::text('name', null, ['id'=>'name-edit', 'class'=>'form-control']) !!}

            </div>

              {!! Form::hidden('user_id', null, ['id'=>'user-id-edit']) !!}

            <div class="form-group">

              {!! Form::label('mobile', 'Mobile: ') !!}
              {!! Form::number('mobile', null, ['id'=>'mobile-edit', 'class'=>'form-control']) !!}

            </div>

            <div class="form-group">

              {!! Form::label('email', 'Email: ') !!}
              {!! Form::email('email', null, ['id'=>'email-edit', 'class'=>'form-control']) !!}

            </div>

            <div class="form-group">

              {!! Form::label('user_role', 'User Role: ') !!}
              {!! Form::select('user_role', $user_roles, null, ['id'=>'user-role-edit', 'class'=>'form-control']) !!}

            </div>

            <div class="form-group">

              {!! Form::label('password', 'Set password: ') !!}
              <input id="password" type="password" class="form-control" name="password" required>

            </div>

          </div>

      </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        {{ Form::submit('Update', ['class'=>'btn btn-primary']) }}

      </div>

      {!! Form::close() !!}

    </div>

  </div>

</div>

@section('footer_scripts')

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this user?</p>
        {{ Form::open(['route' => 'delete.user']) }}

          {{ Form::hidden('user_id', null, ['id'=>'user-id']) }}
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Yes</button>
        {{ Form::close() }}
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>

  </div>
</div>


<script type="text/javascript">
  
  function editUser(elem) {

    var user_id = elem.id;

    var name;
    var mobile;
    var email;
    var user_role;
    var password;
    

    $.ajax({

      type: 'get',
      url: '{!! URL::to('getUserInformation') !!}',
      data: {'user_id': user_id},

      success:function(data) {

        document.getElementById('name-edit').value = data.name;
        document.getElementById('mobile-edit').value = data.mobile;
        document.getElementById('email-edit').value = data.email;
        document.getElementById('user-role-edit').value = data.user_role;

        document.getElementById('user-id-edit').value = user_id;

      }

    });

    $("#editUserModal").modal();

  }

  function deleteClient(elem){
    document.getElementById('user-id').value = elem.id
    $('#myModal').modal();
  }

</script>

@endsection

@endsection