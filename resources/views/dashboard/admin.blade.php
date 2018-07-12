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
</div>

@endsection