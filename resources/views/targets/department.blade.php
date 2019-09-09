@extends('layouts.master')

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

@section('content')

<div class="container-fluid">

   @if($errors->any())
       <div class="alert alert-danger">
           <ul>
               @foreach ($errors->all() as $error)
                   <li>{{ $error }}</li>
               @endforeach
           </ul>
       </div>
   @endif

   <div class="panel">
      <div class="panel-heading">
         <h2 class="panel-title text-center"><b>Set Department Target:</b></h2>
      </div>
      <div class="panel-body">
         {{ Form::open(['route' => 'target.department.store', 'autocomplete' => 'off']) }}

            <div class="form-group">
               {{ Form::label('Select Department:') }}
               {{ Form::Select('department', ['processing' => 'Processing', 'counseling' => 'Counseling'], null, ['class' => 'form-control']) }}
            </div>

            <div class="form-group">
               {{ Form::label('Target Type:') }}
               {{ Form::select('duration_type', ['' => 'Select an option', 'month' => 'Month Range', 'range' => 'Date Range'], null, ['class' => 'form-control', 'onchange' => 'onTargetSelect(this)']) }}
            </div>

            <div id="date-container"></div>

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
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Target</th>
               </tr>
            </thead>
            <tbody>
                  @foreach($targets as $target)
                  <tr>
                     <th>{{ ucfirst($target->department) }}</th>
                     <th>{{ $target->month ? Carbon\Carbon::parse($target->month)->format('M Y') : '-' }}</th>
                     <th>{{ $target->start_date ? Carbon\Carbon::parse($target->start_date)->format('d-M-Y') : '-' }}</th>
                     <th>{{ $target->end_date ? Carbon\Carbon::parse($target->end_date)->format('d-M-Y') : '-' }}</th>
                     <th>{{ $target->target }}</th>
                  </tr>
                  @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>

@section('footer_scripts')

   <script type="text/javascript">
      
      function onTargetSelect(elem) {

         var optionValue = elem.value;

         $('#date-container').empty();

         if(optionValue == 'month') {

            var monthRangeHTML = `

               <div class="form-group">

                  {{ Form::label('Select Month:') }}

                  <input type="month" name="month" class="form-control" required="required">

               </div>

            `;

            
            $('#date-container').append(monthRangeHTML);


         } else if(optionValue == 'range') {

            var dateRangeHTML = `

               <div class="row">

                  <div class="col-md-6">

                     <div class="form-group">

                        {{ Form::label('Start Date:') }}

                        <input type="date" name="start_date" class="form-control" required="required">

                     </div>

                  </div>

                  <div class="col-md-6">

                     <div class="form-group">

                        {{ Form::label('End Date:') }}

                        <input type="date" name="end_date" class="form-control" required="required">

                     </div>

                  </div>

               </div>

            `;


            $('#date-container').append(dateRangeHTML);

         } else {

            return 0;

         }
         
      }

   </script>

@endsection

@endsection