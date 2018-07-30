@extends('layouts.master')

@section('title', 'My Tasks')

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Counselor::Task Lists</h3>
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
               @foreach($counselor_targets as $counselor_target)
               		@foreach($counselor_target->userInfo->where('user_role', 'counselor') as $counselor)
		               <tr>
		               	  <td>{{ $counselor->name }}</td>
		               	  <td>{{ $counselor_target->target }}</td>
		               	  <td>
		               	  	<a href="{{ route('set.target', $counselor->id) }}" class="btn btn-primary btn-block button2">Set Target</a>
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