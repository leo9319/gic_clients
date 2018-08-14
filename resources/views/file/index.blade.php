@extends('layouts.master')

@section('title', 'Profile')

@section('content')

<div class="container-fluid">

   <div class="panel panel-profile" style="height: 500px">

      <div class="clearfix">

         <div class="profile-left">

            <div class="profile-header">

               <div class="overlay"></div>

               <div class="profile-main">

                  <img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">

                  <h3 class="name">{{ Auth::user()->name }}</h3>

                  <span>

                     <a href="#">

                        <i class="fa fa-edit"></i> 

                           Edit Profile

                     </a>

                  </span>

               </div>

            </div>

            <div class="profile-detail">

               <div class="profile-info">

                  <h4 class="heading">Basic Info</h4>

                  <ul class="list-unstyled list-justify">

                     <li>Client Code <span>{{ Auth::user()->client_code }}</span></li>

                     <li>Mobile <span>{{ Auth::user()->mobile }}</span></li>

                     <li>Email <span>{{ Auth::user()->email }}</span></li>

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

            <h4 class="heading">{{ Auth::user()->name }}</h4>

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

                           @foreach($program_progresses as $program_name => $program_progress)

                              <tr>

                                 <td><a href="#">{{ $program_name }}</a></td>

                                 <td>

                                    <div class="progress">

                                       <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: {{$program_progress}}%;">

                                          <span>{{ number_format($program_progress, 0) }}% Complete</span>

                                       </div>

                                    </div>

                                 </td>

                                 <td><span class="label label-success">ACTIVE</span></td>

                              </tr>

                              @endforeach

                        </tbody>

                     </table>

                  </div>

               </div>

            </div>

         </div>

      </div>

   </div>

</div>

@endsection