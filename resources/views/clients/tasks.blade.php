@extends('layouts.master')

@section('title', 'My Tasks')

@section('header_scripts')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready( function () {
       $('#tasks').DataTable({
         'columnDefs' : [
            {
               'searchable' : false,
               'targets' : 5
            }
         ]
       });
   } );
</script>
@stop

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-heading">
         <table style="width:25%">
           <tr>
             <th>Total Tasks:</th>
             <td>{{ $all_tasks->count() }}</td>
           </tr>
           <tr>
             <th class="text-success">Total Completed:</th>
             <td>{{ $all_tasks->where('status', 'complete')->count() }}</td>
           </tr>
           <tr>
             <th class="text-danger">Incomplete Tasks:</th>
             <td>{{ $all_tasks->where('status', '!=', 'complete')->count() }}</td>
           </tr>
         </table>
      </div>
   </div>

   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Task Lists</h3>
         @if(Auth::user()->user_role != 'client')
         <div class="right">
            <a href="#" type="button" class="btn btn-success button2" data-toggle="modal" data-target="#addTask">Add Task</a>
         </div>
         @endif
      </div>
      <div class="panel-body">
         <table id="tasks" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th>SL.</th>
                  <th>Task</th>
                  <th>Deadline</th>
                  <th>Status</th>
                  <th>Approved / Disapproved By</th>
                  <th>Uploaded File</th>

                  @if(Auth::user()->user_role == 'admin' | Auth::user()->user_role == 'rm' | Auth::user()->user_role == 'counselor')
                  
                  <th>Action</th>
                  
                  @endif

                  @if(Auth::user()->user_role == 'client')
                    <th>Action</th>
                    <th>Action</th>
                  @endif

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

                        @if($task->approval == 1)

                          <td>Approved by: {{ App\User::find($task->approved_by)->name }}</td>

                        @elseif($task->approval == 0)

                          <td>Disapproved by: {{ App\User::find($task->approved_by)->name }}</td>

                        @else

                          <td></td> 

                        @endif

                        @if($task->uploaded_file_name)
                          <td><a href="{{ route('download', $task->uploaded_file_name) }}">View File</a></td>
                        @else 
                          <td></td>
                        @endif

                        

                        @if(Auth::user()->user_role == 'admin' | Auth::user()->user_role == 'rm' | Auth::user()->user_role == 'counselor')
                        <td>
                          <a href="{{ route('task.approval', [$task->id, 1]) }}" class="label label-success">Approve</a>
                          <a href="{{ route('task.approval', [$task->id, 0]) }}" class="label label-danger">Disapprove</a>
                        </td>
                        
                        @endif

                        @if(Auth::user()->user_role == 'client')

                          {!! Form::open(['route'=>'update.client.task', 'files' => true]) !!}

                            {{ Form::hidden('task_id', $task->id) }}
                            {{ Form::hidden('client_id', $task->client_id) }}

                          @if($task_info->file_upload == 1)
                          <td>
                              {{ Form::file('uploaded_file_name') }}
                          </td>

                          @elseif($task_info->form_name != 'None Selected')
                            <td><a href="#">Go To Form</a></td>

                          @else
                            <td>
                              {{ Form::radio('status', 'complete') }} Complete
                              <br>
                              {{ Form::radio('status', 'incomplete') }} Incomplete
                            </td>
                          @endif

                          <td>
                              {{ Form::submit('Submit', ['class'=>'btn btn-success btn-xs button2']) }}
                            {!! Form::close() !!}
                          </td>
                        @endif

                        <td>
                          <a href="{{ route('comment.tasks', $task->id) }}" class="label label-info">Comment</a>
                        </td>

                     </tr>
                     @endforeach
               @endforeach
            </tbody>
            <tfoot>
               <tr>
                  <th>SL.</th>
                  <th>Task</th>
                  <th>Deadline</th>
                  <th>Status</th>
                  <th>Approved / Disapproved By</th>
                  <th>Uploaded File</th>

                  @if(Auth::user()->user_role == 'admin' | Auth::user()->user_role == 'rm' | Auth::user()->user_role == 'counselor')
                  
                  <th>Action</th>
                  
                  @endif

                  @if(Auth::user()->user_role == 'client')
                    <th>Action</th>
                    <th>Action</th>
                  @endif

                  <th>Add Comment</th>
               </tr>
            </tfoot>
         </table>
      </div>
   </div>
</div>

<!-- Modal -->
<div id="addTask" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Task</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(['route'=>['client.task.individual.store', $all_tasks->first()->step_id, $all_tasks->first()->client_id]]) !!}
          <div class="form-group">
            {{ Form::label('Task Name:') }}
            {{ Form::text('task_name', null, ['class'=>'form-control']) }}
          </div>

          <div class="form-group">
            {{ Form::label('Task Types:' , null, ['class' => 'control-label']) }}

            <table class="table table-striped">
              <tbody>
                <tr>
                  <td>File Upload</td>
                  <td>{{ Form::checkbox('file_upload', '1') }}</td>
                </tr>
                <tr>
                  <td>With deadline</td>
                  <td>
                    {{ Form::date('deadline', null, ['class'=>'form-control']) }}
                  </td>
                </tr>
                <tr>
                  <td>Form Fillup</td>
                  <td>{{ Form::select('form_name', ['None Selected' => 'None Selected', 'S' => 'Need to change'], null, ['class'=>'form-control']) }}</td>
                </tr>
              </tbody>
            </table>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        {{ Form::submit('Add', ['class'=>'btn btn-primary']) }}
      </div>
      {!! Form::close() !!}
    </div>

  </div>
</div>
@endsection