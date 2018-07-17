@extends('layouts.master')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid">
   <div class="panel panel-headline">
      <div class="panel-heading">
         <h3 class="panel-title">Overview</h3>
         <p class="panel-subtitle">Till: {{ Carbon\Carbon::now()->format('d-M-y') }}</p>
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
                        <td><a href="#"><span class="label label-info">CHECK STATUS</span></a></td>
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
      <div class="col-md-6">
         <div class="row">
            <div class="col-md-3">
               <div class="metric">
                  <div class="row">
                     <div class="col-md-12">
                        <span class="icon"><i class="fa fa-download"></i></span>
                     </div>
                     <div class="col-md-12">
                        <p>
                           <span class="number"><strong>??</strong></span>
                           <span class="title">Not available yet</span>
                        </p>
                     </div>
                  </div>
               </div>
            </div><div class="col-md-3">
               <div class="metric">
                  <div class="row">
                     <div class="col-md-12">
                        <span class="icon"><i class="fa fa-shopping-bag"></i></span>
                     </div>
                     <div class="col-md-12">
                        <p>
                           <span class="number"><strong>{{ $total_files_opened->count() }}</strong></span>
                           <span class="title">Total File Count</span>
                        </p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="metric">
                  <div class="row">
                     <div class="col-md-12">
                        <span class="icon"><i class="fa fa-shopping-bag"></i></span>
                     </div>
                     <div class="col-md-12">
                        <p>
                           <span class="number"><strong>{{ $files_opened_this_month->count() }}</strong></span>
                           <span class="title">Total File Count This Month</span>
                        </p>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-md-3">
               <div class="metric">
                  <div class="row">
                     <div class="col-md-12">
                        <span class="icon"><i class="fa fa-shopping-bag"></i></span>
                     </div>
                     <div class="col-md-12">
                        <p>
                           <span class="number"><strong>12</strong></span>
                           <span class="title">Files Left To Achieve</span>
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection