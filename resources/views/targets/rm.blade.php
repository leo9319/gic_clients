@extends('layouts.master')

@section('title', 'My Tasks')

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">RM::Task Lists</h3>
         <div class="right">
            <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
         </div>
      </div>
      <div class="panel-body">
         <table id="tasks" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th>Name</th>
                  <th>Current Target</th>
                  <th class="text-center">Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($rm_targets as $rm_target)
               		@foreach($rm_target->userInfo->where('user_role', 'rm') as $rm)
		               <tr>
		               	  <td>{{ $rm->name }}</td>
		               	  <td>{{ $rm_target->target }}</td>
		               	  <td>
		               	  	<a href="{{ route('set.target', $rm->id) }}" class="btn btn-primary btn-block button2">Set Target</a>
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