@extends('layouts.master')

@section('url', $previous)

@section('title', 'Counselor Targets')

@section('header_scripts')
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
   <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
   <script>
      $(document).ready( function () {
          $('#rms').DataTable({
            'columnDefs' : [
               {
                  'searchable' : false,
                  'targets' : 2
               }
            ]
          });
      } );
   </script>
@stop

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Counselor::Targets</h3>
         <div class="right">
            <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
         </div>
      </div>
      <div class="panel-body">
         <table id="rms" class="table table-striped">
            <thead>
               <tr>
                  <th>SL.</th>
                  <th>Counselor</th>
                  <th>Assigned Clients</th>
               </tr>
            </thead>
            <tbody>
               @foreach($counselors as $index => $counselor)
                  <tr>
                     <th>{{ $index + 1 }}</th>
                     <th>{{ $counselor->name }}</th>
                     <th><a href="{{ route('set.target', $counselor->id) }}"><button class="btn btn-primary btn-block button2">Set/View Targets</button></a></th>
                  </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection