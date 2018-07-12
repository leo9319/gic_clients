@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
   <!-- OVERVIEW -->
   <div class="panel panel-headline">
      <div class="panel-heading">
         <h3 class="panel-title">Overview</h3>
         <p class="panel-subtitle">Till: {{ Carbon\Carbon::now()->format('d-M-y') }}</p>
      </div>
      <div class="panel-body">
         <div class="row">
                  <div class="col-md-12">
                     <!-- RECENT PURCHASES -->
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
                                             <td><a href="#">{{ $index + 1 }}</a></td>
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
                     <!-- END RECENT PURCHASES -->
                  </div>
               </div>
         <div class="row">
            <div class="col-md-9">
               <div id="headline-chart" class="ct-chart"></div>
            </div>
         </div>
      </div>
   </div>
</div>

@endsection