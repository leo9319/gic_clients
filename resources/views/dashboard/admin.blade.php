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
               <h3 class="panel-title">Recent User Activity</h3>
               <div class="right">
                  <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                  <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
               </div>
            </div>
            <div class="panel-body">
               <ul class="list-unstyled activity-list">
                  <li>
                     <img src="assets/img/user1.png" alt="Avatar" class="img-circle pull-left avatar">
                     <p><a href="#">Michael</a> has achieved 80% of his completed tasks <span class="timestamp">20 minutes ago</span></p>
                  </li>
                  <li>
                     <img src="assets/img/user2.png" alt="Avatar" class="img-circle pull-left avatar">
                     <p><a href="#">Daniel</a> has been added as a team member to project <a href="#">System Update</a> <span class="timestamp">Yesterday</span></p>
                  </li>
                  <li>
                     <img src="assets/img/user3.png" alt="Avatar" class="img-circle pull-left avatar">
                     <p><a href="#">Martha</a> created a new heatmap view <a href="#">Landing Page</a> <span class="timestamp">2 days ago</span></p>
                  </li>
                  <li>
                     <img src="assets/img/user4.png" alt="Avatar" class="img-circle pull-left avatar">
                     <p><a href="#">Jane</a> has completed all of the tasks <span class="timestamp">2 days ago</span></p>
                  </li>
                  <li>
                     <img src="assets/img/user5.png" alt="Avatar" class="img-circle pull-left avatar">
                     <p><a href="#">Jason</a> started a discussion about <a href="#">Weekly Meeting</a> <span class="timestamp">3 days ago</span></p>
                  </li>
               </ul>
               <button type="button" class="btn btn-primary btn-bottom center-block">Load More</button>
            </div>
         </div>
      </div>
      <div class="col-md-6">
         <!-- RECENT PURCHASES -->
         <div class="panel">
            <div class="panel-heading">
               <h3 class="panel-title">Recent Purchases</h3>
               <div class="right">
                  <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
                  <button type="button" class="btn-remove"><i class="lnr lnr-cross"></i></button>
               </div>
            </div>
            <div class="panel-body no-padding">
               <table class="table table-striped">
                  <thead>
                     <tr>
                        <th>Order No.</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Date &amp; Time</th>
                        <th>Status</th>
                     </tr>
                  </thead>
                  <tbody>
                     <tr>
                        <td><a href="#">763648</a></td>
                        <td>Steve</td>
                        <td>$122</td>
                        <td>Oct 21, 2016</td>
                        <td><span class="label label-success">COMPLETED</span></td>
                     </tr>
                     <tr>
                        <td><a href="#">763649</a></td>
                        <td>Amber</td>
                        <td>$62</td>
                        <td>Oct 21, 2016</td>
                        <td><span class="label label-warning">PENDING</span></td>
                     </tr>
                     <tr>
                        <td><a href="#">763650</a></td>
                        <td>Michael</td>
                        <td>$34</td>
                        <td>Oct 18, 2016</td>
                        <td><span class="label label-danger">FAILED</span></td>
                     </tr>
                     <tr>
                        <td><a href="#">763651</a></td>
                        <td>Roger</td>
                        <td>$186</td>
                        <td>Oct 17, 2016</td>
                        <td><span class="label label-success">SUCCESS</span></td>
                     </tr>
                     <tr>
                        <td><a href="#">763652</a></td>
                        <td>Smith</td>
                        <td>$362</td>
                        <td>Oct 16, 2016</td>
                        <td><span class="label label-success">SUCCESS</span></td>
                     </tr>
                  </tbody>
               </table>
            </div>
            <div class="panel-footer">
               <div class="row">
                  <div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o"></i> Last 24 hours</span></div>
                  <div class="col-md-6 text-right"><a href="#" class="btn btn-primary">View All Purchases</a></div>
               </div>
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