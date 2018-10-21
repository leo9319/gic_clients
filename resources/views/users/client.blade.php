@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'My Clients')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready( function () {
       $('#users').DataTable({
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
   <div class="panel panel-headline">
      <div class="panel-body">
        <h2>My Clients</h2>
      </div>
      <div class="panel-footer">
         <table id="users" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th>Client ID.</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  @if(Auth::user()->user_role == 'counselor')
                  <th>RM</th>
                  @endif
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
              @foreach($assigned_clients as $index => $assigned_client)
                @foreach($assigned_client->clients as $index => $client)
                @foreach($client->getAssignedRms as $assigned_rm)
                 <tr>
                    <td><a href="{{ route('client.profile', ['client_id'=> $client->id ]) }}">{{ $client->client_code }}</a></td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->mobile }}</td>
                    <td>{{ $client->email }}</td>
                    @if(Auth::user()->user_role == 'counselor')
                    <td>{{ App\User::find($assigned_rm->rm_id) ? App\User::find($assigned_rm->rm_id)->name : "" }}</td>
                    @endif
                    <td>
                       <a href="{{ route('client.action', $client->id) }}" class="btn btn-outline-warning button2">View Actions</a>
                    </td>
                 </tr>
                 @endforeach
                 @endforeach
               @endforeach
            </tbody>
            <tfoot>
               <tr>
                  <th>Client ID.</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  @if(Auth::user()->user_role == 'counselor')
                  <th>RM</th>
                  @endif
                  <th>Action</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>
@endsection