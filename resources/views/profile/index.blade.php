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

            <h4 class="heading">Programs</h4>

            <div class="tab-content">

               <div class="tab-pane fade in active" id="tab-bottom-left3">

                  <div class="table-responsive">

                     <table class="table project-table">

                        <thead>

                           <tr>

                              <th>Program Name</th>

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

	                                       <div class="progress-bar" role="progressbar" aria-valuenow="{{ number_format($program_progresses[$pi->id]) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ number_format($program_progresses[$pi->id]) }}%;">

	                                          <span>{{ number_format($program_progresses[$pi->id]) }}</span>

	                                       </div>

	                                    </div>

	                                 </td>

                                    <td>

                                       <a href="{{ route('client.steps', [$pi->id, $client->id]) }}"><span class="label label-info">VIEW STEPS</span></a>               

                                    </td>

                                    <td>

                                       <span class="label label-success">ACTIVE</span>

                                    </td>

	                              </tr>

	                              @endforeach

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