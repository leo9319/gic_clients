@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'My Tasks')

@section('header_scripts')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
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

{{-- Task Stats --}}
@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-heading">
         <table style="width:25%">
           <tr>
             <th>Total Tasks:</th>
             <td></td>
           </tr>
           <tr>
             <th class="text-success">Total Completed:</th>
             <td></td>
           </tr>
           <tr>
             <th class="text-danger">Incomplete Tasks:</th>
             <td></td>
           </tr>
         </table>
      </div>
   </div>

   <div class="panel">
      <div class="panel-heading">

         <h3 class="panel-title">Task Lists</h3>

          <div class="right">
            <a href="#" type="button" class="btn btn-success button2" data-toggle="modal" data-target="#addTask">Add Task</a>
          </div>

      </div>

      <div class="panel-body">
         <table id="tasks" class="table table-striped table-bordered" style="width:100%">

            <thead>
               <tr>
                  <th>SL.</th>
                  <th>Task</th>
                  <th>Client Name</th>
                  <th>Deadline</th>
                  <th>Approved / Disapproved</th>
                  <th>Uploaded File</th>
                  <th>Action / Status</th>
                  <th>Add Comment</th>
               </tr>
            </thead>

            <tbody>
               @foreach($all_tasks as $index => $task)
                     @foreach($task->tasks as $task_info)
                     <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $task_info->task_name }}</td>
                        <td>{{ App\User::find($task->client_id)->name }}</td>
                        <td>{{ Carbon\Carbon::parse($task->deadline)->format('dS M') }}</td>

                        @if($task->approval == 1)
                          <td class="text-success"><b>Approved by client</b></td>
                        @elseif($task->approval == 0)
                          <td class="text-danger"><b>Dispproved by client</b></td>
                        @else
                          <td class="text-warning"><b>Decision pending</b></td>
                        @endif

                        @if($task->uploaded_file_name)
                          <td><a href="{{ route('download', $task->uploaded_file_name) }}">View File</a></td>
                        @else 
                          <td></td>
                        @endif

                        <td>

                          @if($task_info->file_upload != 1)

                            @if($task->status == 'complete' || $task->status == 'pending')

                              <p class="text-success"><b>Complete</b></p>

                            @else

                              <a href="{{ route('task.update.status', [$task->id, 1]) }}" class="label label-success label-sm label-block" style="max-width: 90px">Complete</a>

                              <a href="{{ route('task.update.status', [$task->id, 0]) }}" class="label label-danger label-sm" style="max-width: 90px">Incomplete</a>

                            @endif

                          @else

                            <a href="#" name="{{ $task->id }}" class="label label-warning label-sm" onclick="uploadFile(this)">Upload File</a>

                          @endif

                        </div>
                          
                        </td>

                        <td>
                          <a href="{{ route('comment.user.tasks', $task->id) }}" class="label label-info">Comment</a>
                        </td>

                     </tr>
                     @endforeach
               @endforeach
            </tbody>

            <tfoot>
               <tr>
                  <th>SL.</th>
                  <th>Task</th>
                  <th>Client Name</th>
                  <th>Deadline</th>
                  <th>Approved / Disapproved</th>
                  <th>Uploaded File</th>
                  <th>Action / Status</th>
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
        {!! Form::open(['route' => 'task.user.create']) !!}
          <div class="form-group">
            {{ Form::label('Task Name:') }}
            {{ Form::text('task_name', null, ['class'=>'form-control']) }}
            
          </div>

          <div class="form-group">
            {{ Form::label('Client Name:') }}
            <br>
            {{ Form::select('client_id', $clients->pluck('name', 'id'), null, ['class'=>'form-control select2', 'id'=>'client-id', 'style'=>'width:550px']) }}
            
          </div>

          <div class="form-group">
            {{ Form::label('Select Program:') }}
            <br>
            {{ Form::select('program_id', $programs->pluck('program_name', 'id'), null,['class'=>'form-control select2', 'id'=>'program-id', 'style'=>'width:550px', 'onchange'=>'getStep(this)']) }}
            
          </div>

          <div class="form-group">
            {{ Form::label('Select Step:') }}
            <br>
            {{ Form::select('step_id', [], null, ['class'=>'form-control select2', 'id'=>'step-id', 'style'=>'width:550px']) }}
            
          </div>



          {{ Form::hidden('user_id', $user_id) }}

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

<!-- File Upload Modal -->
<div id="fileUploadModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">File Upload</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(['route'=>'task.upload.file', 'files' => true]) !!}

          <div class="form-group">
            
            {{ Form::label('File Upload:') }}
            {{ Form::file('uploaded_file_name', ['class'=>'form-control']) }}
            {{ Form::hidden('task_id', null, ['id'=>'task-id']) }}

          </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        {{ Form::submit('Upload', ['class'=>'btn btn-info']) }}

        {{ Form::close() }}
      </div>
    </div>

  </div>
</div>

@section('footer_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">
$("#client-id").select2({
        placeholder: 'Select a Client', 
        allowClear: true
});

$("#program-id").select2({
        placeholder: 'Select a Program', 
        allowClear: true
});

$("#step-id").select2({
        placeholder: 'Select a Step', 
        allowClear: true
});

</script>

<script type="text/javascript">
  
  function uploadFile(elem) {

    document.getElementById('task-id').value = elem.name;
    $('#fileUploadModal').modal();

  }

  function getStep(elem) {
    var program_id = document.getElementById('program-id').value;
    var op = '';

    $.ajax({
      type: 'get',
      url: '{!! URL::to('getStep') !!}',
      data: {'program_id':program_id},
      success:function(data) {
        for (var i = 0; i < data.length; i++) {
          op+='<option value="'+data[i].id+'">'+data[i].step_name+'</option>';
        }
        
        document.getElementById('step-id').innerHTML = op;
      },
      error:function(){

      }

    });
  }

</script>

@endsection
@endsection