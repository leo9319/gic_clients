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

                  <span class="icon"><i class="fa fa-download"></i></span>

                  <p>

                     <span class="number">{{ $target->sum('achieved') }}</span>

                     <span class="title">Total File Count</span>

                     

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-sitemap"></i></span>

                  <p>

                     <span class="number">

                     {{ $target->whereDate('month_year', Carbon\Carbon::parse()->format('Y-m-01'))->first()->target }}

                     </span>
                     
                     <span class="title">Target This Month</span>

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-eye"></i></span>

                  <p>

                     <span class="number">

                        {{ $target->whereDate('month_year', Carbon\Carbon::parse()->format('Y-m-01'))->first()->achieved }}

                     </span>

                     <span class="title">Total File This Month</span>

                  </p>

               </div>

            </div>

            <div class="col-md-3">

               <div class="metric">

                  <span class="icon"><i class="fa fa-bar-chart"></i></span>

                  <p>
                     
                     <span class="number">

                        {{ 
                           $target->whereDate('month_year', Carbon\Carbon::parse()->format('Y-m-01'))->first()->target 

                           - 

                           $target->whereDate('month_year', Carbon\Carbon::parse()->format('Y-m-01'))->first()->achieved

                        }}

                     </span>
                     <span class="title">Files Left To Achieve</span>

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

               <h3 class="panel-title">Appointments Today</h3>

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
                        <th>Name</th>
                        <th>Status</th>
                     </tr>

                  </thead>

                  <tbody>

                     @forelse($appointments as $index => $appointment)
                     @forelse($appointment->client as $client)
                     <tr>

                        <td>{{ $index + 1 }}</td>
                        <td>{{ $client->name }}</td>
                        <td><a href="#"><span class="label label-info">CHECK STATUS</span></a></td>

                     </tr>
                     @empty

                     @endforelse

                     @empty

                     <tr>

                        <td class="text-center" colspan="3">No Appointments Today</td>

                     </tr>

                     @endforelse

                  </tbody>

               </table>

            </div>

            <div class="panel-footer">

               <div class="row">

                  <div class="col-md-6 text-right"><a href="#" class="btn btn-default">View All</a></div>

               </div>

            </div>

         </div>

      </div>

      <div class="col-md-6">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">File Opened This Month</h3>

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

                        <th>Name</th>

                        <th>Status</th>

                     </tr>

                  </thead>

                  <tbody>

                     @forelse($files_opened_this_month as $index => $fotm)

                     @forelse($fotm->clients as $client)

                     <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $client->name }}</td>

                        <td><a href="{{ route('client.profile', $client->id) }}"><span class="label label-info">CHECK PROFILE</span></a></td>

                     </tr>

                     @empty

                     @endforelse

                     @empty

                     <tr>

                        <td class="text-center" colspan="3">No Files Opened This Month</td>

                     </tr>

                     @endforelse

                  </tbody>

               </table>

            </div>

            <div class="panel-footer">

               <div class="row">

                  <div class="col-md-6 text-right"><a href="#" class="btn btn-default">View All</a></div>

               </div>

            </div>

         </div>

      </div>

      <div class="col-md-6">

         <div class="panel">

            <div class="panel-heading">

               <h3 class="panel-title">Today's Prospecting</h3>

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

                        <th>Name</th>

                        <th></th>

                     </tr>

                  </thead>

                  <tbody>

                     @forelse($appointments as $index => $appointment)

                     @forelse($appointment->client as $client)

                     <tr>

                        <td>{{ $index + 1 }}</td>

                        <td>{{ $client->name }}</td>

                        <td><a href="#"><span class="label label-success">CALL</span></a></td>

                     </tr>

                     @empty

                     @endforelse

                     @empty

                     <tr>

                        <td class="text-center" colspan="3">No Prospecting Today</td>

                     </tr>

                     @endforelse

                  </tbody>

               </table>

            </div>

            <div class="panel-footer">

               <div class="row">

                  <div class="col-md-6 text-right"><a href="#" class="btn btn-default">View All</a></div>

               </div>

            </div>

         </div>

      </div>

      

   </div>

</div>

@endsection