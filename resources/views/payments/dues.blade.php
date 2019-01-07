@extends('layouts.master')

@section('url', $previous)

@section('title', 'Dues')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

{{-- <script>

   $(document).ready( function () {

       $('#client_dues').DataTable({

       	'columnDefs' : [

       		{

       		}

       	]

       });

   });

</script> --}}

<script>
$(function() {

  var $tableSel = $('#client_dues');
  $tableSel.dataTable({
    "ordering": false,
     dom: 'Bfrtip',
        buttons: [
            'csv',
            'excel',
            {
                extend: 'print',
                text: 'Print all (not just selected)',
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                }
            },
            {
                text: 'Select all',
                action: function () {
                    this.rows().select();
                }
            },
            {
                text: 'Select none',
                action: function () {
                    this.rows().deselect();
                }
            }
        ],
        select: true
  });
  
  $('#filter').on('click', function(e){
    e.preventDefault();
    var startDate = $('#start').val(),
        endDate = $('#end').val();
    
    filterByDate(0, startDate, endDate); // We call our filter function
    
    $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
  });
  
  // Clear the filter. Unlike normal filters in Datatables,
  // custom filters need to be removed from the afnFiltering array.
  $('#clearFilter').on('click', function(e){
    e.preventDefault();
    $.fn.dataTableExt.afnFiltering.length = 0;
    $tableSel.dataTable().fnDraw();
  });
  
});

var filterByDate = function(column, startDate, endDate) {
  // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
          var rowDate = normalizeDate(aData[column]),
              start = normalizeDate(startDate),
              end = normalizeDate(endDate);

          
          // If our date from the row is between the start and end
          if (start <= rowDate && rowDate <= end) {
            return true;
          } else if (rowDate >= start && end === '' && start !== ''){
            return true;
          } else if (rowDate <= end && start === '' && end !== ''){
            return true;
          } else {
            return false;
          }
        }
    );
  };

  var normalizeDate = function(dateString) {
  var date = new Date(dateString);
  var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
  return normalized;
}
</script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2 class="text-center">Client Dues</h2>

		</div>

		<div class="panel-footer">

      <table border="0" cellspacing="5" cellpadding="5">
          <tbody>
            <tr>
              <td>Start Date: </td>
              <td><input type="date" id="start" name="min" class="form-control"></td>
            </tr>
            <tr>
                <td>End Date: </td>
                <td><input type="date" id="end" name="max" class="form-control"></td>
            </tr>
          </tbody>
        </table>

        <hr>

        <button id="filter" class="btn btn-success btn-sm">Filter</button>
        <button id="clearFilter" class="btn btn-info btn-sm" class="btn btn-success">Clear Filter</button>
        <hr>

			<table id="client_dues" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Payment Date</th>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Total Amount</th>

                  <th>Total Paid</th>

                  <th>Due</th>

                  <th>Due Date</th>

                   @if(Auth::user()->user_role == 'accountant' || Auth::user()->user_role == 'admin')

                  <th class="text-center">Action</th>

                  @endif

               </tr>

            </thead>

            <tfoot>

               <tr>

                  <th>Payment Date</th>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Total Amount</th>

                  <th>Total Paid</th>

                  <th>Due</th>

                  <th>Due Date</th>

                  @if(Auth::user()->user_role == 'accountant' || Auth::user()->user_role == 'admin')

                  <th class="text-center">Action</th>

                  @endif

               </tr>

            </tfoot>

            <tbody>
              @foreach($all_dues as $all_due)
            	<tr>

                <td>{{ Carbon\Carbon::parse($all_due->created_at)->format('d-M-y') }}</td>
                

                <td>{{ $all_due->userInfo->name ?? 'Client Deleted' }}</td>

                <td>{{ $all_due->programInfo->program_name ?? 'Program Deleted'}}</td>

                <td>{{ $all_due->stepInfo->step_name ?? 'Step Deleted' }}</td>

                <td>
                  {{ number_format($all_due->opening_fee + $all_due->embassy_student_fee + $all_due->service_solicitor_fee + $all_due->other) }}
                </td>

                <td>
                  {{ 
                    number_format($all_due->totalPayment
                    ->where('cheque_verified', '!=', 0)
                    ->where('online_verified', '!=', 0)
                    ->where('refund_payment', '!=', 1)
                    ->sum('amount_paid')) 
                  }}
                </td>

                <td>{{ number_format($all_due->dues) }}</td>

                <td>{{ Carbon\Carbon::parse($all_due->due_date)->format('d-M-y') }}</td>

                 @if(Auth::user()->user_role == 'accountant' || Auth::user()->user_role == 'admin')
                <td><a href="{{ route('payment.client.dues.details', $all_due->id) }}" class="btn btn-success btn-sm button2">Clear Due</a></td>
                @endif
            	</tr>
              @endforeach
            </tbody>            

         </table>

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>

@endsection