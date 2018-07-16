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
            <div class="col-md-3">
               <div class="metric">
                  <span class="icon"><i class="fa fa-users"></i></span>
                  <p>
                     <span class="number">{{ $number_of_clients }}</span>
                     <span class="title">Clients</span>
                  </p>
               </div>
            </div>
            <div class="col-md-3">
               <div class="metric">
                  <span class="icon"><i class="fa fa-sitemap"></i></span>
                  <p>
                     <span class="number">{{ $number_of_rms }}</span>
                     <span class="title">RMs</span>
                  </p>
               </div>
            </div>
            <div class="col-md-3">
               <div class="metric">
                  <span class="icon"><i class="fa fa-eye"></i></span>
                  <p>
                     <span class="number">{{ $number_of_accountants }}</span>
                     <span class="title">Accountants</span>
                  </p>
               </div>
            </div>
            <div class="col-md-3">
               <div class="metric">
                  <span class="icon"><i class="fa fa-bar-chart"></i></span>
                  <p>
                     <span class="number">{{ $number_of_counsellor }}</span>
                     <span class="title">Counsellors</span>
                  </p>
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
   <div class="row">
      <div class="col-md-6">
         <div class="panel panel-scrolling">
            <div class="panel-heading">
               <h3 class="panel-title">Upcoming Appointments</h3>
               <div class="right">
                  <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                  <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
               </div>
            </div>
            <div class="panel-body">
               <ul class="list-unstyled activity-list">
                  @foreach($appointments as $appointment)
                  @foreach($appointment->client as $client)
                  @foreach($appointment->appointer as $appointer)
                  <li>
                     <i class="fa fa-users"></i>
                     <p><a href="{{ route('client.profile', $client->id) }}">{{ $client->name }}</a> has an appointment with {{ $appointer->name }} on {{ Carbon\Carbon::parse($appointment->app_date)->format('d M') }} at {{ Carbon\Carbon::parse($appointment->app_time)->format('h:i a') }}</p>
                  </li>
                  @endforeach
                  @endforeach
                  @endforeach
               </ul>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="panel">
            <div class="panel-heading">
               <h3 class="panel-title">Recent File Opened (Last 5)</h3>
               <div class="right">
                  <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                  <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
               </div>
            </div>
            <div class="panel-body no-padding">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>Customer ID.</th>
                        <th>Name</th>
                        <th>Date Opened</th>
                        <th>View Profile</th>
                     </tr>
                  </thead>
                  <tbody>
                     @foreach($recent_clients as $index => $recent_client)
                     <tr>
                        <td>{{ $recent_client->client_code }}</td>
                        <td>{{ $recent_client->name }}</td>
                        <td>{{ Carbon\Carbon::parse($recent_client->created_at)->format('d-M-Y') }}</td>
                        <td><a href="{{ route('client.profile', $recent_client->id) }}"><span class="label label-info">VIEW PROFILE</span></a></td>
                     </tr>
                     @endforeach
                  </tbody>
               </table>
            </div>
            <div class="panel-footer">
               <div class="text-center"><a href="#" class="btn btn-defualt">View All</a></div>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <div class="panel">
            <div class="panel-heading">
               <h3 class="panel-title">Website Visits</h3>
               <div class="right">
                  <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                  <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
               </div>
            </div>
            <div class="panel-body">
               <div id="visits-chart" class="ct-chart"></div>
            </div>
         </div>
      </div>
      
   </div>
</div>
@endsection