@extends('layouts.master')

@section('url', $previous)

@section('title', 'Department Targets')

@section('header_scripts')
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
   <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
   <script>
      $(document).ready( function () {
          $('#department').DataTable({
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

@section('content').

<div class="container-fluid">

   <div class="panel">
      <div class="panel-heading">
         <h2 class="panel-title text-center"><b>Set Department Target:</b></h2>
      </div>
      <div class="panel-body">
         {{ Form::open(['route'=>'target.department.store']) }}

            <div class="form-group">
               {{ Form::label('Select Department:') }}
               {{ Form::Select('department', ['processing' => 'Processing', 'counseling' => 'Counseling'], null, ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
               {{ Form::label('Select Month:') }}
               <input type="month" name="month" class="form-control" required="required">
            </div>

            <div class="form-group">
               {{ Form::label('Set Target:') }}
               <input type="number" name="target" class="form-control" required="required">
            </div>

            <br>

            <div class="form-group">
               {{ Form::submit('Submit', ['class'=>'btn btn-info btn-block button2']) }}
            </div>

         {{ Form::close() }}
      </div>
   </div>

   <div class="panel">
      <div class="panel-heading">
         <h3 class="panel-title">Department::Targets</h3>
         <div class="right">
            <button type="button" class="btn-toggle-collapse"><i class="lnr lnr-chevron-up"></i></button>
         </div>
      </div>
      <div class="panel-body">
         <table id="rms" class="table table-striped">
            <thead>
               <tr>
                  <th>Department</th>
                  <th>Month</th>
                  <th>Target</th>
               </tr>
            </thead>
            <tbody>
                  @foreach($targets as $target)
                  <tr>
                     <th>{{ ucfirst($target->department) }}</th>
                     <th>{{ Carbon\Carbon::parse($target->month)->format('M Y') }}</th>
                     <th>{{ $target->target }}</th>
                  </tr>
                  @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection