@extends('layouts.master')

@section('title', 'Tasks')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready(function() {
   	$('#tasks').DataTable();
   });
</script>
@stop

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h3 class="text-center">{{ $user->name }}'s Task ({{ ucfirst($user->user_role) }})</h3>
			
		</div>
		
	</div>

   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Task Lists</h3>
      </div>
      <div class="panel-body">
         <table id="tasks" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th>SL.</th>
                  <th>Task</th>
                  <th>Deadline</th>
                  <th>Status</th>
                  <th>Uploaded File</th>
                  <th>Approve / Disapprove</th>
                  <th>Add Comment</th>
                  
               </tr>
            </thead>
            <tbody>
               @foreach($all_tasks as $index => $task)
                     @foreach($task->tasks as $task_info)
                     <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $task_info->task_name }}</td>
                        <td>{{ Carbon\Carbon::parse($task->deadline)->format('d-m-Y') }}</td>
                        <td>{{ $task->status }}</td>

                        @if($task->uploaded_file_name)
                          <td><a href="{{ route('download', $task->uploaded_file_name) }}">View File</a></td>
                        @else 
                          <td></td>
                        @endif


                        @if($task->approval == -1 || $task->status == 'pending')

              						<td>
              						  	
              							<a href="{{ route('task.approve', [$task->id, 1]) }}" class="label label-success">Approve</a>
              							<a href="{{ route('task.approve', [$task->id, 0]) }}" class="label label-danger">Disapprove</a>

              						</td>

                        @elseif($task->approval == 1)

                          <td><p class="text-success"><b>Approved</b></p></td>

                        @elseif($task->approval == 0)

                          <td><p class="text-danger">Disapproved</p></td>

                        @endif

						@if(Auth::user()->user_role == 'admin' | Auth::user()->user_role == 'rm' | Auth::user()->user_role == 'counselor')
                        <td>
                          <a href="{{ route('task.approval', [$task->id, 1]) }}" class="label label-success">Approve</a>
                          <a href="{{ route('task.approval', [$task->id, 0]) }}" class="label label-danger">Disapprove</a>
                        </td>
                        
                        @endif

                        <td>
                          <a href="{{ route('comment.user.tasks', $task->id) }}" class="label label-info">Comment</a>
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