@extends('layouts.master')

@section('title', 'My Tasks')

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Task Lists</h3>
         <div class="right">
            <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
         </div>
      </div>
      <div class="panel-body">
         <ul class="list-unstyled todo-list">
            @foreach($tasks as $task)
               <li>
                  <div class="row">
                     <div class="col-md-4">
                        <label class="control-inline fancy-checkbox">
                        <i class="fa fa-adjust"></i>
                        </label>
                        <p>
                           <span class="title">{{ $task->task_name }}</span>
                           <br>
                           <span>
                              Status: 
                              @if($task->status == 'complete')
                              <span class="text-success">Complete</span>
                              @elseif($task->status == 'pending')
                              <span class="text-info">Pending</span>
                              @else
                              <span class="text-danger">Incomplete</span>
                              @endif
                           </span>
                           <br>
                           <span>
                              @if($task->approval == 1)
                              <span class="text-success">Approved By: {{ $task->name }}</span>
                              @elseif($task->approval == -1)
                              <span class="text-warning">Pending Approval</span>
                              @else
                              <span class="text-danger">Dissapproved By: {{ $task->name }}</span>
                              @endif
                           </span>
                           <br>
                           @if($task->assigned_date)
                              <span class="text-info">Deadline: {{ Carbon\Carbon::parse($task->assigned_date)->format('d/m/Y') }}</span>
                           @else
                              <span></span>
                           @endif
                           <br>
                           @if($task->status != 'incomplete')
                           <span class="date">Date Submitted: {{ Carbon\Carbon::parse($task->updated_at)->format('d/m/Y') }}</span>
                           @else
                           <span class="date"></span>
                           @endif
                        </p>
                     </div>

                     {{ Form::open(['route'=> ['upload.files', $task->program_id, $task->client_id], 'files'=>true]) }}
                     @if($task->type == 'File upload')          
                     <div class="col-md-4" style="margin-top: 15px;">
                        {{ Form::file('image', ['class'=>'form-control']) }}
                        {{ Form::hidden('task_id', $task->id) }}
                     </div>

                     @else
                     <div class="col-md-4" style="margin-top: 30px;">
                        <label class="control-inline fancy-checkbox">
                              <span></span>
                        </label>
                     </div>
                     
                      @endif

                     {{ Form::hidden('task_id', $task->id) }}

                     @if($task->status != 'complete')
                     <div class="col-md-4">
                        {{ Form::submit('Submit', ['class'=>'btn btn-primary button4', 'style'=>'margin-top: 15px;']) }}
                     </div>
                     @endif

                     {{ Form::close() }} 
                  </div>
               </li>
               @endforeach
         </ul>
      </div>
   </div>

   
</div>




@endsection