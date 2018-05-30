@extends('layouts.master')

@section('title', 'Tasks')

@section('content')
<div class="container-fluid">
	<div class="panel panel-headline">
		<div class="panel-body">
			<h3 class="sub-header-padding">:: Create Task</h3>
			{{ Form::open(['route'=>'task.store']) }}
			<div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::text('task_name', null, ['class'=>'form-control', 'placeholder'=>'Task Name']) !!}
                  </div>
               </div>

               <div class="field-spacing"">
                  <div class="input-group">
                     <span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     {!! Form::select('task_type', [
                     'Task with deadline' => 'Task with deadline', 
                     'Task with without deadline' => 'Task without deadline',
                     'File upload' => 'File upload', 
                     'Form fillup' => 'Form fillup'
                     ], null, ['class'=>'form-control']) !!}
                  </div>
               </div>

               @if(session()->has('message'))
               		<div class="alert alert-success alert-dismissible">
				    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
				    <strong>Success!</strong> {{ session()->get('message') }}
				  </div>
				    
				@endif

               {{ Form::submit('Submit', ['class'=>'btn btn-primary btn-block', 'style'=>'margin-top: 30px; border-radius: 20px;']) }}
			{{ Form::close() }}
		</div>
	</div>

   <div class="panel">
      <div class="panel-body">
         <h3 class="sub-header-padding">:: List of tasks</h3>
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>SL.</th>
                  <th>Task Name</th>
                  <th>Task Type</th>
               </tr>
            </thead>
            <tbody>
               @foreach($tasks as $index => $task)
               <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $task->task_name}}</td>
                  <td>{{ $task->task_type}}</td>
               </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection