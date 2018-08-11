@extends('layouts.master')

@section('title', 'Task Comments')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>{{ $task->task_name }}</h2>

		</div>

		<div class="panel-footer">

			<div class="container-fluid" style="margin: 20px">

				<ul class="list-unstyled activity-timeline">

					@foreach($comments as $comment)

						@foreach($comment->users as $user)

						<li>

							<i class="fa fa-comment activity-icon"></i>

							<p>{{ $user->name }}</p>

							<p>

								<q>{{ $comment->comment }}</q>

								<span class="timestamp">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($comment->
									created_at))->diffForHumans()  }}</span>

							</p>

						</li>

						@endforeach

					@endforeach

				</ul>

			</div>

		</div>

	</div>


	<div class="panel">
		
		<div class="panel-body">
			
			<h3>Add Comment</h3>
			
		</div>
		
		<div class="panel-footer">
			
			{!! Form::open(['route'=>['comment.spouse.tasks.store', 1]]) !!}
			
				{!! Form::textarea('comment', null, ['class'=>'form-control', 'rows' => 5]) !!}
				{!! Form::hidden('spouse_task_id', $spouse_task->id) !!}
				
				{{ Form::submit('Add Comment', ['class'=>'btn btn-info button2 btn-block', 
				'style'=>'margin-top:20px;']) }}
				
			{!! Form::close() !!}
			
		</div>
		
	</div>
	
</div>

@endsection