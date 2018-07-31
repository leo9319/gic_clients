@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">
   <div class="panel panel-profile">
      <div class="clearfix">
         <div class="profile-left">
            <div class="profile-header">
               <div class="overlay"></div>
               <div class="profile-main">
                  <img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">
                  <h3 class="name">{{ $client->name }}</h3>
                  <span><a href="#"><i class="fa fa-edit"></i> Edit Profile</a></span>
               </div>
            </div>
            <div class="profile-detail">
               <div class="profile-info">
                  <h4 class="heading">Basic Info</h4>
                  <ul class="list-unstyled list-justify">
                     <li>Client Code <span>{{ $client->client_code }}</span></li>
                     <li>Mobile <span>{{ $client->mobile }}</span></li>
                     <li>Email <span>{{ $client->email }}</span></li>
                  </ul>
               </div>
               <div class="profile-info">
                  <h4 class="heading">Social</h4>
                  <ul class="list-inline social-icons">
                     <li><a href="#" class="facebook-bg"><i class="fa fa-facebook"></i></a></li>
                     <li><a href="#" class="twitter-bg"><i class="fa fa-twitter"></i></a></li>
                     <li><a href="#" class="google-plus-bg"><i class="fa fa-google-plus"></i></a></li>
                     <li><a href="#" class="github-bg"><i class="fa fa-github"></i></a></li>
                  </ul>
               </div>
               <div class="profile-info">
               </div>
            </div>
         </div>
         <div class="profile-right">
            <h4 class="heading">{{ $client->name }}</h4>
            <!-- AWARDS -->
            <!-- <div class="awards">
               <div class="row">
                  <div class="col-md-3 col-sm-6">
                     <div class="award-item">
                        <div class="hexagon">
                           <span class="lnr lnr-sun award-icon"></span>
                        </div>
                        <span>Most Bright Idea</span>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-6">
                     <div class="award-item">
                        <div class="hexagon">
                           <span class="lnr lnr-clock award-icon"></span>
                        </div>
                        <span>Most On-Time</span>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-6">
                     <div class="award-item">
                        <div class="hexagon">
                           <span class="lnr lnr-magic-wand award-icon"></span>
                        </div>
                        <span>Problem Solver</span>
                     </div>
                  </div>
                  <div class="col-md-3 col-sm-6">
                     <div class="award-item">
                        <div class="hexagon">
                           <span class="lnr lnr-heart award-icon"></span>
                        </div>
                        <span>Most Loved</span>
                     </div>
                  </div>
               </div>
               <div class="text-center"><a href="#" class="btn btn-default">See all awards</a></div>
            </div> -->
            <!-- END AWARDS -->
            <!-- TABBED CONTENT -->
            <div class="custom-tabs-line tabs-line-bottom left-aligned">
               <ul class="nav" role="tablist">
                  <!-- <li class="active"><a href="#group-bottom-left1" role="tab" data-toggle="tab">Completed Group Tasks</a></li>
                  <li><a href="#group-bottom-left2" role="tab" data-toggle="tab">Pending Group Tasks <span class="badge"></span></a></li>
                  <li class=""><a href="#tab-bottom-left1" role="tab" data-toggle="tab">Completed Tasks</a></li>
                  <li><a href="#tab-bottom-left2" role="tab" data-toggle="tab">Pending Tasks <span class="badge"></span></a></li> -->
                  <li><a href="#tab-bottom-left3" role="tab" data-toggle="tab">Programs <span class="badge"></span></a></li>
               </ul>
            </div>
            <div class="tab-content">
               <!-- <div class="tab-pane fade in active" id="group-bottom-left1">
                  <ul class="list-unstyled activity-timeline">
                     @foreach($client_group_completed_tasks as $client_group_completed_task)
                      <li>
                         <i class="fa fa-check activity-icon"></i>
                         <p> <a href="#"></a> <span class="timestamp">{{ $client_group_completed_task-> task_name}}</span></p>
                      </li>
                      @endforeach
                  </ul>
               </div> -->
               <!-- <div class="tab-pane fade in" id="group-bottom-left2">
                  <ul class="list-unstyled activity-timeline">
                     @foreach($client_group_pending_tasks as $client_group_pending_task)
                      <li>
                         <i class="fa fa-check activity-icon"></i>
                         <p> <a href="#"></a> <span class="timestamp">{{ $client_group_pending_task-> task_name}}</span></p>
                      </li>
                      @endforeach
                  </ul>
               </div> -->
               <!-- <div class="tab-pane fade in" id="tab-bottom-left1">
                  <ul class="list-unstyled activity-timeline">
                  	@foreach($client_complete_tasks as $client_complete_task)
                  		@foreach($client_complete_task->tasks as $task)
		                   <li>
		                      <i class="fa fa-check activity-icon"></i>
		                      <p> <a href="#"></a> <span class="timestamp">{{ $task->task_name }}</span></p>
		                   </li>
	                   @endforeach
                   @endforeach
                  </ul>
               </div> -->
               <!-- <div class="tab-pane fade in" id="tab-bottom-left2">
                  <ul class="list-unstyled activity-timeline">
                  	@foreach($client_pending_tasks as $client_pending_task)
                  		@foreach($client_pending_task->tasks as $task)
		                   <li>
		                      <i class="fa fa-times activity-icon"></i>
		                      <p> <a href="#"></a> <span class="timestamp">{{ $task->task_name }}</span></p>
		                   </li>
	                   @endforeach
                   @endforeach
                  </ul>
               </div> -->
               <div class="tab-pane fade in active" id="tab-bottom-left3">
                  <div class="table-responsive">
                     <table class="table project-table">
                        <thead>
                           <tr>
                              <th>Title</th>
                              <th>Progress</th>
                              <th>View Tasks</th>
                              <th>Status</th>                            
                           </tr>
                        </thead>
                        <tbody>
                        	@foreach($client_programs as $client_program)
                        		@foreach($client_program->programInfo as $pi)
	                              <tr>
	                                 <td><a href="#"></a>{{ $pi->program_name }}</td>
	                                 <td>
	                                    <div class="progress">
	                                       <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
	                                          <span>60% Complete</span>
	                                       </div>
	                                    </div>
	                                 </td>
                                    <td><a href="{{ route('client.steps', [$pi->id, $client->id]) }}" class="btn btn-primary btn-sm button2">View Tasks</a></td>
                                    <td><span class="label label-success">ACTIVE</span></td>
	                              </tr>
	                              @endforeach
                             @endforeach
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
            <!-- END TABBED CONTENT -->
         </div>
         <!-- END RIGHT COLUMN -->
      </div>
   </div>
</div>
@endsection