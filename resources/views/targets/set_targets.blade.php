@extends('layouts.master') 

@section('title', 'Tasks') 

@section('content')

@section('header_scripts')
   <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
   <script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
   <script>
      $(document).ready( function () {
          $('#individual-target').DataTable({
            'columnDefs' : [
               {
                  'searchable' : false,
                  'targets' : 3
               }
            ]
          });
      } );
   </script>
@stop

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

      <div class="panel-body">
         <h3 class="sub-header-padding">Target Histroy | {{ $user->name }} </h3>
      </div>

      <button type="button" class="btn btn-success button2 pull-right" style="margin: 20px 25px 20px 0px" data-toggle="modal" data-target="#exampleModal">
        Add Target
      </button>
         
     <div class="panel-footer">

         <table class="table table-striped table-bordered" id="individual-target">

             <thead>

                 <tr>
                     <th>SL.</th>
                     <th>Period</th>
                     <th>Start Date</th>
                     <th>End Date</th>
                     <th>Target</th>
                     <th>Achieved</th>
                 </tr>

             </thead>

             <tbody>

                 @foreach($targets as $index => $target)

                 <tr>
                     <td>{{ $index + 1 }}</td>
                     <td>{{ $target->month_year ? Carbon\Carbon::parse($target->month_year)->format('F Y') : '-'}}</td>
                     <td>{{ $target->start_date ? Carbon\Carbon::parse($target->start_date)->format('d-M-Y') : '-' }}</td>

                     <td>{{ $target->end_date ? Carbon\Carbon::parse($target->end_date)->format('d-M-Y') : '-' }}</td>
                     <td>{{ $target->target }}</td>
                     <td>{{ $target->getIndividualTargetAchieved($target->user_id, $target->month_year, $target->start_date, $target->end_date) }}</td>
                 </tr>

                 @endforeach

             </tbody>

         </table>

        </div>

    </div>

</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Add Target</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

          <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        {{ Form::open(['route'=>['store.target', $user->id]]) }}

         <div class="form-group">

            {!! Form::label('target') !!}

            {!! Form::text('target', null, ['class'=>'form-control', 'placeholder'=>'Target', 'required']) !!}

         </div>

         <div class="form-group">

            {{ Form::label('Target Type:') }}

            {{ Form::select('duration_type', ['' => 'Select an option', 'month' => 'Month Range', 'range' => 'Date Range'], null, ['class' => 'form-control', 'onchange' => 'onTargetSelect(this)']) }}

         </div>

         <div id="date-container"></div>

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