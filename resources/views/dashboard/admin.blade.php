@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')

<div class="container-fluid">

   <div class="panel panel-headline">

      <div class="panel-heading">

         <h3 class="panel-title">Overview</h3>

         <p class="panel-subtitle">{{ Carbon\Carbon::now()->format('jS F Y') }}</p>

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

                  <span class="icon"><i class="fa fa-book"></i></span>

                  <p>

                     <span class="number">{{ $number_of_accountants }}</span>
                     <span class="title">Accountants</span>

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-user"></i></span>

                  <p>
                     <span class="number">{{ $number_of_counsellor }}</span>
                     <span class="title">Counsellors</span>

                  </p>

               </div>

            </div>

         </div>

      </div>

   </div>

   <div class="row">

      <div class="col-md-6">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">Payments</h3>

               <div class="right">

                  <button type="button" class="btn-toggle-collapse">

                     <i class="lnr lnr-chevron-up"></i>

                  </button>

                  <button type="button" class="btn-remove">

                     <i class="lnr lnr-cross"></i>

                  </button>

               </div>

            </div>

            <div class="panel-body no-padding">

               <table class="table table-striped">

                  <thead>

                     <tr>

                        <th>Customer ID.</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Step</th>
                        <th>Total Amount</th>
                        <th>Amount Paid</th>
                        
                     </tr>

                  </thead>

                  <tbody>

                     @foreach($payments as $payment)

                        @foreach($payment->userInfo as $user)

                        <tr>

                           <td>{{ $user->client_code }}</td>
                           <td>{{ $user->name }}</td>
                           <td>{{ App\Program::find($payment->program_id)->program_name }}</td>
                           <td>{{ $payment->step_no }}</td>
                           <td>{{ $payment->total_amount }}</td>
                           <td>{{ $payment->amount_paid }}</td>

                        </tr>

                        @endforeach

                     @endforeach

                  </tbody>

               </table>

            </div>

            <div class="panel-footer">

               <div class="row">

                  <div class="col-md-6">

                     <span class="panel-note">

                     <i class="fa fa-clock-o">
                     
                     </i> Latest 5</span>

                  </div>

                  <div class="col-md-6 text-right">

                     <a href="{{ route('payment.history') }}" class="btn btn-info btn-xs">All Payments</a>

                  </div>

               </div>

            </div>

         </div>

      </div>

      <div class="col-md-6">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">Recent File Opened</h3>

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
                        @foreach($recent_client->userInfo as $client)

                     <tr>

                        <td>{{ $client->client_code }}</td>
                        <td>{{ $client->name }}</td>
                        <td>{{ Carbon\Carbon::parse($client->created_at)->format('d-M-Y') }}</td>
                        <td>
                           <a href="{{ route('client.profile', $recent_client->client_id) }}"><span class="label label-primary">VIEW PROFILE</span></a>
                        </td>

                     </tr>

                        @endforeach
                     @endforeach

                  </tbody>

               </table>

            </div>

            <div class="panel-footer">

               <div class="row">

                  <div class="col-md-6"><span class="panel-note"><i class="fa fa-clock-o">
                     
                     </i> Latest 5</span>

                  </div>

                  <div class="col-md-6 text-right">

                     <a href="{{ route('client.index') }}" class="btn btn-info btn-xs">All Clients</a>

                  </div>

               </div>

            </div>

         </div>

      </div>

   </div>

   <div class="row">

      <!-- Upcoming Appointments -->

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
                                 <p>

                                    <a href="{{ route('client.profile', $client->id) }}">{{ $client->name }}</a> 
                                    has an appointment with 
                                    <a href="{{ route('client.profile', $appointer->id) }}">{{ $appointer->name }}</a> 
                                     on {{ Carbon\Carbon::parse($appointment->app_date)->format('d M') }} at {{ Carbon\Carbon::parse($appointment->app_time)->format('h:i a') }}</p>

                              </li>

                           @endforeach

                        @endforeach

                  @endforeach

               </ul>

            </div>

         </div>

      </div>

      <!-- Number of Clients -->

      <div class="col-md-6">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">Clients Per Program</h3>

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

   <div class="row">

      <div class="col-md-12">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">Targets</h3>

               <div class="right">

                  <button type="button" class="btn-toggle-collapse">

                     <i class="lnr lnr-chevron-up"></i>

                  </button>

                  <button type="button" class="btn-remove">

                     <i class="lnr lnr-cross"></i>

                  </button>

               </div>

            </div>

            <div class="panel-body no-padding">

               <table class="table table-striped">

                  <thead>

                     <tr>

                        <th>Employee Name</th>
                        <th>Role</th>
                        <th>Target</th>
                        <th>Achieved</th>
                        <th>Month</th>
                        
                     </tr>

                  </thead>

                  <tbody>

                     @foreach($targets as $index => $target)

                        @foreach($target->userInfo as $user)

                        <tr>

                           <td>{{ $user->name }}</td>
                           <td>{{ ucfirst($user->user_role) }}</td>
                           <td>{{ $target->target }}</td>
                           <td>{{ $target->achieved }}</td>
                           <td>{{ Carbon\Carbon::parse($target->month_year)->format('F') }}</td>

                        </tr>

                        @endforeach

                     @endforeach

                  </tbody>

               </table>

            </div>

            <div class="panel-footer">

               <div class="row">

                  <div class="col-md-6">

                     <span class="panel-note">

                     <i class="fa fa-clock-o">
                     
                     </i> Latest 5</span>

                  </div>

                  <div class="col-md-6 text-right">

                     <a href="{{ route('target.rm') }}" class="btn btn-info btn-xs">View All RMs</a>
                     <a href="{{ route('target.counselor') }}" class="btn btn-info btn-xs">View All Counselors</a>

                  </div>

               </div>

            </div>

         </div>

      </div>

   </div>
      
</div>

@section('footer_scripts')
<script>

   var program = [];
   var count = [];

   $(function() {

      var data, options, series_array;

      $.ajax({

         type: 'get',
         url: '{!!URL::to('findClientProgram')!!}',
         success:function(data) {

            for (var i = 0; i < data.length; i++) {
               program[i] = data[i].program_name;
               count[i] = data[i].total;
               
            }

            new Chartist.Line('#visits-trends-chart', data, options);

            data = {
               labels: program,
               series: [
                  count
               ]
            };

            options = {
               height: 400,
               axisX: {
                  showGrid: false
               },
            };

            new Chartist.Bar('#visits-chart', data, options);

         },

         error: function(data) {             
            
         },

   });

   

      

      

   });

</script>
@endsection

@endsection