@extends('layouts.master')

@section('title', 'Department Targets')

@section('header_scripts')
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
   <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
   <script>
      $(document).ready( function () {
          $('#department-targets').DataTable({
            'columnDefs' : [
               {
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
         <h3 class="panel-title">Department Targets</h3>
      </div>

      <button type="button" class="btn btn-success pull-right button2" style="margin: 25px" data-toggle="modal" data-target="#addTarget">
        Add Target
      </button>

      <div class="panel-body">

         <table id="department-targets" class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>Department</th>
                  <th>Month</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Target</th>
                  <th>Achieved</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
                  @foreach($department_targets as $department_target)
                  <tr>
                     <th>{{ ucfirst($department_target->department) }}</th>

                     <th>{{ $department_target->month ? Carbon\Carbon::parse($department_target->month)->format('M Y') : '-' }}</th>

                     <th>{{ $department_target->start_date ? Carbon\Carbon::parse($department_target->start_date)->format('d-M-Y') : '-' }}</th>

                     <th>{{ $department_target->end_date ? Carbon\Carbon::parse($department_target->end_date)->format('d-M-Y') : '-' }}</th>

                     <th>{{ $department_target->target }}</th>

                     <th>{{ $department_target->getTargetAchieved($department_target->department, $department_target->month, $department_target->start_date, $department_target->end_date) }}</th>
                     
                     <th><a href="{{ route('target.department.details', $department_target->id) }}" class="btn btn-info button2">View Details</a></th>
                  </tr>
                  @endforeach
            </tbody>
            <thead>
               <tr>
                  <th>Department</th>
                  <th>Month</th>
                  <th>Start Date</th>
                  <th>End Date</th>
                  <th>Target</th>
                  <th>Achieved</th>
                  <th>Action</th>
               </tr>
            </thead>
         </table>

      </div>
   </div>
</div>

<!-- Add Target Modal -->
<div class="modal fade" id="addTarget" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Target</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

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

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save changes</button>
      </div>

      {{ Form::close() }}
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