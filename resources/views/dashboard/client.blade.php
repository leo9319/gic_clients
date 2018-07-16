@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
   <div class="panel panel-headline">
      <div class="panel-heading">
         <h3 class="panel-title">Overview</h3>
         <p class="panel-subtitle">Till: {{ Carbon\Carbon::now()->format('d-M-y') }}</p>
      </div>
   </div>
   <div class="panel-body">
      <div class="row">
         <div class="col-md-12">
            <div class="panel">
               <div class="panel-heading">
                  <h3 class="panel-title">My Progress</h3>
                  <div class="right">
                     <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                     <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                  </div>
               </div>
               <div class="panel-body">
                  <ul class="list-unstyled task-list">
                     @foreach($program_progresses as $index => $program_progress)
                     <li>
                        <p>{{ $index }} <span class="label-percent">{{ number_format($program_progress, 0) }}%</span></p>
                        <div class="progress progress-xs">
                           <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="23" aria-valuemin="0" aria-valuemax="100" style="width:{{ $program_progress }}%">
                           </div>
                        </div>
                     </li>
                     @endforeach
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-md-8">
            <div class="panel">
               <div class="panel-heading">
                  <h3 class="panel-title">My Tasks</h3>
                  <div class="right">
                     <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                     <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                  </div>
               </div>
               <div class="panel-body no-padding">
                  <table class="table table-striped">
                     <thead>
                        <tr>
                           <th>SL.</th>
                           <th>Task Name</th>
                           <th>Task Type</th>
                           <th>Program Name</th>
                           <th>Date &amp; Time</th>
                           <th>Status</th>
                        </tr>
                     </thead>
                     <tbody>
                        @foreach($tasks as $index => $task)
                        @foreach($task->tasks as $task_detail)
                        @foreach($task_detail->types as $task_type)
                        @foreach($task_detail->programs as $program)
                        <tr>
                           <td>{{ $index + 1 }}</td>
                           <td>{{ $task_detail->task_name }}</td>
                           <td>{{ $task_type->type }}</td>
                           <td>{{ $program->program_name }}</td>
                           <td>{{ $task->assigned_date }}</td>
                           <td><span class="label label-success">{{ $task->status }}</span></td>
                        </tr>
                        @endforeach
                        @endforeach
                        @endforeach
                        @endforeach
                     </tbody>
                  </table>
               </div>
               <div class="panel-footer">
                  <div class="row">
                     <div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o"></i> Last 24 hours</span></div>
                     <div class="col-md-6 text-right"><a href="#" class="btn btn-primary">View All Tasks</a></div>
                  </div>
               </div>
            </div>
         </div>
         <div class="col-md-4">
            <div class="panel">
               <div class="panel-heading">
                  <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i> Incompleted Tasks</h3>
                  <div class="right">
                     <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                     <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                  </div>
               </div>
               <div class="panel-body">
                  <ul class="list-unstyled todo-list">
                     @forelse($tasks->where('assigned_date', '<', Carbon\Carbon::now()) as $task)
                     @forelse($task->tasks as $task_detail)
                     <li>
                        <p>
                           <span class="title text-danger">{{ $task_detail->task_name  }}</span>
                           <span class="short-description"></span>
                           <span class="date">Deadline: {{ Carbon\Carbon::parse($task->assigned_date)->format('d-M-Y (D)') }}</span>
                        </p>
                        <div class="controls">
                           <a href="#"><i class="icon-software icon-software-pencil"></i></a> <a href="#"><i class="icon-arrows icon-arrows-circle-remove"></i></a>
                        </div>
                     </li>
                     @empty
                     @endforelse
                     @empty
                     <li>No tasks has exceeded the deadline yet!</li>
                     @endforelse
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-md-7">
            <div class="panel panel-scrolling">
               <div class="panel-heading">
                  <h3 class="panel-title">My Upcoming Appointments</h3>
                  <div class="right">
                     <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                     <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
                  </div>
               </div>
               <div class="panel-body">
                  <ul class="list-unstyled activity-list">
                     @forelse($appointments as $appointment)
                     @forelse($appointment->appointer as $appointer_profile)
                     <li>
                        <i class="fa fa-calendar"></i>
                        <p>You have an appointment with {{ $appointer_profile->name }} on {{ Carbon\Carbon::parse($appointment->app_date)->format('d-M-Y') }} at {{ Carbon\Carbon::parse($appointment->app_time)->format('h:i a') }}<span class="timestamp">{{Carbon\Carbon::parse($appointment->app_date)->diffInDays(Carbon\Carbon::now(), false) }} day(s) to go</span></p>
                     </li>
                     @empty
                     @endforelse
                     @empty
                     <li>
                        <p>You dont have any upcoming appointments!</p>
                     </li>
                     @endforelse
                  </ul>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-9">
            <div id="headline-chart" class="ct-chart"></div>
         </div>
      </div>
   </div>
</div>
@endsection