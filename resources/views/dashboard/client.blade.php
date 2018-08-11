@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

   <div class="panel panel-headline">

      <div class="panel-heading">

         <h3 class="panel-title">Overview</h3>

         <p class="panel-subtitle">Till: {{ Carbon\Carbon::now()->format('d-M-y') }}</p>

      </div>

      <div class="panel-footer">

         <div class="row">

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-download"></i></span>

                  <p>

                     <span class="number">{{ $task_count }}</span>

                     <span class="title">Total Tasks</span>

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-sitemap"></i></span>

                  <p>

                     <span class="number">{{ $program_count }}</span>
                     
                     <span class="title">Total Programs</span>

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-eye"></i></span>

                  <p>

                     <span class="number">{{ $rm_client_count }}</span>

                     <span class="title">Assigend RMs</span>

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-bar-chart"></i></span>

                  <p>
                     
                     <span class="number">{{ $counselor_client_count }}</span>

                     <span class="title">Assigned Counselors</span>

                  </p>

               </div>

            </div>

         </div>

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

                        <p>{{ App\Program::find($index)->program_name}} <span class="label-percent">{{ number_format($program_progress, 0) }}%</span>

                        </p>

                        <div class="progress progress-xs">

                           <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="23" 
                           aria-valuemin="0" aria-valuemax="100" style="width:{{ $program_progress }}%">

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

                           <th>Task Name</th>

                           <th>Deadline</th>

                           <th>Status</th>

                        </tr>

                     </thead>

                     <tbody>

                        @foreach($client_tasks as $client_task)

                           @foreach($client_task->tasks as $task)

                           <tr>

                              <td>{{ $task->task_name }}</td>

                              <td>{{ $client_task->deadline }}</td>

                              <td>{{ $client_task->status }}</td>

                           </tr>         

                           @endforeach

                        @endforeach

                     </tbody>
                     
                  </table>

               </div>

               <div class="panel-footer">

                  <div class="row">

                     <div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o"></i> Last 5</span>

                     </div>

                     <div class="col-md-6 text-right"><a href="{{ route('client.myprograms', Auth::user()->id) }}" class="btn btn-primary">View All Tasks</a></div>
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

                  @forelse($client_incomplete_tasks as $client_incomplete_task)
                     @forelse($client_incomplete_task->tasks as $task)

                     <ul class="list-unstyled todo-list">

                        {{ $task->task_name }}

                     </ul>

                     @empty

                     @endforelse

                     @empty

                     <ul>No incomplete tasks</ul>

                  @endforelse

               </div>
            </div>
         </div>

         <div class="col-md-12">

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

                        <p>You have an appointment with {{ $appointer_profile->name }} on 

                           {{ Carbon\Carbon::parse($appointment->app_date)->format('d-M-Y') }} at 
                           {{ Carbon\Carbon::parse($appointment->app_time)->format('h:i a') }}

                           <span class="timestamp">
                             
                            {{ \Carbon\Carbon::createFromTimeStamp(strtotime($appointment->app_date . $appointment->app_time))->diffForHumans()  }}

                         </span>
                      </p>

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