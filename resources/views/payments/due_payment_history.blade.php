@extends('layouts.master')

@section('title', 'Due Payment History')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
$(function() {

  var $tableSel = $('#due-payment-history');
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
            },
            'colvis'
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

			<h2>Due Payment History</h2>

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

			<table id="due-payment-history" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Date</th>

                  <th>Client Code.</th>

                  <th>Client Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  <th>Counselor</th>

                  <th>RM</th>

                  <th>Due Paid</th>

                  <th>Remaining Due</th>

                  <th>Note</th>

                  <th>Generate Invoice</th>

               </tr>

            </thead>

            <tbody>
              @foreach($due_payments as $due_payment)
              <tr>
                <td>{{ Carbon\Carbon::parse($due_payment->due_cleared_date)->format('d-M-y') }}</td>
                <td>{{ $due_payment->userInfo->client_code ?? 'Client Removed' }}</td>
                <td>{{ $due_payment->userInfo->name ?? 'Client Removed' }}</td>
                <td>{{ $due_payment->programInfo->program_name }}</td>
                <td>{{ $due_payment->stepInfo->step_name }}</td>

                <td>

                  @foreach($due_payment->userInfo->getAssignedCounselors as $counselors)

                    {{ $counselors->user->name ?? 'N/A'}}

                  @endforeach

                  </td>

                  <td>

                  @foreach($due_payment->userInfo->getAssignedRms as $rms)

                    {{ $rms->user->name ?? 'N/A'}}

                  @endforeach

                </td>

                <td>{{ number_format($due_payment->totalPayment->where('due_payment', 1)->sum('amount_paid')) }}</td>
                <td>{{ number_format($due_payment->dues) }}</td>
                <td>{{ $due_payment->comments }}</td>
                <td><a href="{{ route('payment.client.dues.pdf', $due_payment->id) }}" class="btn btn-primary btn-sm button button2">Generate Invoice</a></td>
              </tr>
              @endforeach
            </tbody>

            <tfoot>

               <tr>

                  <th>Date</th>

                  <th>Client Code.</th>

                  <th>Client Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  <th>Counselor</th>
                  
                  <th>RM</th>

                  <th>Due Paid</th>

                  <th>Remaining Due</th>

                  <th>Note</th>

                  <th>Generate Invoice</th>

               </tr>

            </tfoot>

         </table>

		</div>

	</div>

</div>

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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>

@endsection