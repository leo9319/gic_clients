@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">	
	<div class="panel-body">
		<div class="profile-header">
	       <div class="overlay"></div>
	       <div class="profile-main">
	          <img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">
	          <h3 class="name">{{ $client->name }}</h3>
	       </div> 
	    </div>
	</div>

	<div class="panel">
		<div class="panel-body">
			<div class="profile-header"></div>
			<h3 class="text-center">{{ $program->program_name }}</h3>

			<div class="custom-tabs-line tabs-line-bottom left-aligned">
               <ul class="nav" role="tablist">
               	<li class="active"><a href="#group-bottom-left1" role="tab" data-toggle="tab">All Tasks</a></li>
                  <li class=""><a href="#group-bottom-left2" role="tab" data-toggle="tab">Completed Tasks</a></li>
                  <li><a href="#group-bottom-left3" role="tab" data-toggle="tab">Pending Tasks <span class="badge"></span></a></li>
               </ul>
            </div>

            <div class="tab-content">
            	<div class="tab-pane fade in active" id="group-bottom-left1">
                  <ul class="list-unstyled activity-timeline">
                  	 @foreach($all_tasks as $all_task)
	                      <li>
	                         <i class="fa fa-tasks activity-icon"></i>
	                         <p> <a href="#"></a> <span class="timestamp">{{ $all_task->task_name }}</span></p>
	                      </li>
                      @endforeach
                  </ul>

                  <ul class="list-unstyled activity-timeline">
                  	@foreach($individual_tasks as  $individual_task)
                  		@foreach($individual_task->tasks as $task)
	                  	<li>
	                  		<i class="fa fa-tasks activity-icon"></i>
	                  		<p> <a href="#"></a> <span class="timestamp">{{ $task->task_name }}</span></p>
	                  	</li>
	                  	@endforeach
                  	@endforeach
                  </ul>
              		
          		  <h4>Assign More Tasks::</h4>

			         {{ Form::open(['route' => ['task.add.individual', $client->id, $program->id]]) }}
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
			         <div class="field-spacing"">
			            <div class="input-group">
			               <span class="input-group-addon"><i class="fa fa-globe"></i></span>
			               {!! Form::select('rm_id', $all_rms->pluck('name', 'id'), null, ['class'=>'form-control']) !!}
			            </div>
			         </div>
			         <div class="field-spacing"">
			            <div class="input-group">
			               <span class="input-group-addon"><i class="fa fa-globe"></i></span>
			               {!! Form::date('deadline', null, ['class'=>'form-control']) !!}
			            </div>
			         </div>
			         {{ Form::submit('Add', ['class'=>'btn btn-primary btn-block', 'style'=>'margin-top: 30px; border-radius: 20px;']) }}
			         {{ Form::close() }}

          		  <hr>
               </div>

               <div class="tab-pane fade in" id="group-bottom-left2">
               		<ul class="list-unstyled activity-timeline">
                  	 @foreach($complete_group as $complete)
	                      <li>
	                         <i class="fa fa-check activity-icon"></i>
	                         <p> <a href="#"></a> <span class="timestamp">{{ $complete->task_name }}</span></p>
	                      </li>
                      @endforeach
                  </ul>

                  <ul class="list-unstyled activity-timeline">
                  	 @foreach($individual_completed_tasks as $individual_completed_task)
                  	 	@foreach($individual_completed_task->tasks as $completed_task)
	                      <li>
	                         <i class="fa fa-check activity-icon"></i>
	                         <p> <a href="#"></a> <span class="timestamp">{{ $completed_task->task_name }}</span></p>
	                      </li>
	                      @endforeach
                      @endforeach
                  </ul>
               </div>

               <div class="tab-pane fade in" id="group-bottom-left3">
               		<ul class="list-unstyled activity-timeline">
                  	 @foreach($pending_group as $pending)
	                      <li>
	                         <i class="fa fa-times activity-icon"></i>
	                         <p> <a href="#"></a> <span class="timestamp">{{ $pending->task_name }}</span></p>
	                      </li>
                      @endforeach
                  </ul>

                  <ul class="list-unstyled activity-timeline">
                  	 @foreach($individual_pending_tasks as $individual_pending_task)
                  	 	@foreach($individual_pending_task->tasks as $pending_task)
	                      <li>
	                         <i class="fa fa-times activity-icon"></i>
	                         <p> <a href="#"></a> <span class="timestamp">{{ $pending_task->task_name }}</span></p>
	                      </li>
	                      @endforeach
                      @endforeach
                  </ul>
               </div>
            </div>
		</div>
	</div>
</div>
@endsection