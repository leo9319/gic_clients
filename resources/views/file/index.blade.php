@extends('layouts.master')

@section('title', 'Profile')

@section('content')
<div class="container-fluid">
   <div class="panel panel-profile">
      <div class="clearfix">
         <!-- LEFT COLUMN -->
         <div class="profile-left">
            <!-- PROFILE HEADER -->
            <div class="profile-header">
               <div class="overlay"></div>
               <div class="profile-main">
                  <img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">
                  <h3 class="name">{{ Auth::user()->name }}</h3>
                  <span><a href="#"><i class="fa fa-edit"></i> Edit Profile</a></span>
               </div>
            </div>
            <!-- END PROFILE HEADER -->
            <!-- PROFILE DETAIL -->
            <div class="profile-detail">
               <div class="profile-info">
                  <h4 class="heading">Basic Info</h4>
                  <ul class="list-unstyled list-justify">
                     <li>Client Code <span>{{ Auth::user()->client_code }}</span></li>
                     <li>Mobile <span>{{ Auth::user()->mobile }}</span></li>
                     <li>Email <span>{{ Auth::user()->email }}</span></li>
                     <!-- <li>Website <span><a href="https://www.themeineed.com">N/A</a></span></li> -->
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
            <!-- END PROFILE DETAIL -->
         </div>
         <!-- END LEFT COLUMN -->
         <!-- RIGHT COLUMN -->
         <div class="profile-right">
            <h4 class="heading">{{ Auth::user()->name }}</h4>
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
                  <li class="active"><a href="#tab-bottom-left1" role="tab" data-toggle="tab">Recent Activity</a></li>
                  <li><a href="#tab-bottom-left2" role="tab" data-toggle="tab">Programs <span class="badge">{{ count($client_programs) }}</span></a></li>
               </ul>
            </div>
            <div class="tab-content">
               <div class="tab-pane fade in active" id="tab-bottom-left1">
                  <ul class="list-unstyled activity-timeline">
                     @foreach($completed_tasks as $completed_task)
                        @foreach($completed_task->tasks as $task)
                           <li>
                              <i class="fa fa-check activity-icon"></i>
                              <p>{{ $task->task_name }} <a href="#"></a>{{ $task->uploaded_file_name }} <span class="timestamp">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($task->updated_at))->diffForHumans() }}</span></p>
                           </li>
                        @endforeach
                     @endforeach
                  </ul>
                  <div class="margin-top-30 text-center"><a href="#" class="btn btn-default">See all activity</a></div>
               </div>
               <div class="tab-pane fade" id="tab-bottom-left2">
                  <div class="table-responsive">
                     <table class="table project-table">
                        <thead>
                           <tr>
                              <th>Title</th>
                              <th>Progress</th>
                              <th>Status</th>
                           </tr>
                        </thead>
                        <tbody>
                           @foreach($client_programs as $client_program)
                              @foreach($client_program->programInfo as $program)
                              <tr>
                                 <td><a href="#">{{ $program->program_name }}</a></td>
                                 <td>
                                    <div class="progress">
                                       <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
                                          <span>60% Complete</span>
                                       </div>
                                    </div>
                                 </td>
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