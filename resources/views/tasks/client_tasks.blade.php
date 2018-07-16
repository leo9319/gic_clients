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
               	<li class=""><a href="#group-bottom-left1" role="tab" data-toggle="tab">All Tasks</a></li>
                  <li><a href="#group-bottom-left2" role="tab" data-toggle="tab">Completed Tasks</a></li>
                  <li class="active"><a href="#group-bottom-left3" role="tab" data-toggle="tab">Pending Tasks <span class="badge"></span></a></li>
               </ul>
            </div>

            <div class="tab-content">
            	<div class="tab-pane fade in" id="group-bottom-left1">
                  <ul class="list-unstyled activity-timeline">
                  	 @foreach($all_tasks as $all_task)
              	 		@foreach($all_task->tasks as $task)
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
			               {!! Form::select('type_id', $task_types->pluck('type', 'id'), null, ['class'=>'form-control']) !!}
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
                  		@foreach($complete_tasks as $complete_task)
               				@foreach($complete_task->tasks as $c_task)
		                      <li> 
		                         <i class="fa fa-check activity-icon"></i>
		                         <p>{{ $c_task->task_name }}</p>
		                      </li>
	                    	@endforeach
	                    @endforeach
                  </ul>
               </div>

               <div class="tab-pane fade in active" id="group-bottom-left3">
               		<ul class="list-unstyled activity-timeline">
               			@foreach($pending_tasks as $pending_task)
               				@foreach($pending_task->tasks as $p_task)
		                      <li>
		                         <i class="fa fa-times activity-icon"></i>
		                         <p>{{ $p_task->task_name }}</p>
		                         <span class="pull-right">
		                         	@if($pending_task->approval == 1)
		                         	<strong class="text-success">Approved</strong>
		                         	@elseif($pending_task->approval == 0)
		                         	<strong class="text-danger">Dissapproved</strong>
		                         	@endif

		                         	@if($pending_task->approval == -1)
		                         	<a href="{{ route('task.approval', [$pending_task, 1]) }}" onclick='check()' class="btn btn-success btn-sm">Approve</a>
     		                        <a href="{{ route('task.approval', [$pending_task, 0]) }}" class="btn btn-danger btn-sm">Disapprove</a>
     		                        @endif
     		                    </span>
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

