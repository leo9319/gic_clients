{{-- Admin --}}

@extends('layouts.master')

@section('title', 'bKash Corporate')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
$(function() {

  var $tableSel = $('#unverified-bkash-corporate');
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

      <h2>bKash Corporate</h2>

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

      <table id="unverified-bkash-corporate" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>
                  <th>Date</th>
                  <th>Client Code</th>
                  <th>Client Name</th>
                  <th>Program Name</th>
                  <th>Step Name</th>
                  <th>Counselor</th>
                  <th>RM</th>
                  <th>Status</th>
                  <th>Amount</th>
               </tr>

            </thead>

            <tbody>

              @foreach($unverified_bkashes_corporate as $unverified_bkash_corporate)

                    <tr>
                      <td>{{ Carbon\Carbon::parse($unverified_bkash_corporate->created_at)->format('d-M-y') }}</td>
                      <td>{{ $unverified_bkash_corporate->payment->userInfo->client_code ?? 'Client Removed' }}</td>
                      <td>{{ $unverified_bkash_corporate->payment->userInfo->name ?? 'Client Removed' }}</td>
                      <td>{{ $unverified_bkash_corporate->payment->programInfo->program_name ?? 'N/A' }}</td>
                      <td>{{ $unverified_bkash_corporate->payment->stepInfo->step_name ?? 'N/A' }}</td>

                      <td>

                        @foreach($unverified_bkash_corporate->payment->userInfo->getAssignedCounselors as $counselors)

                          {{ $counselors->user->name ?? 'N/A'}}

                        @endforeach

                        </td>

                        <td>

                        @foreach($unverified_bkash_corporate->payment->userInfo->getAssignedRms as $rms)

                          {{ $rms->user->name ?? 'N/A'}}

                        @endforeach

                      </td>

                      <td>
                        @if($unverified_bkash_corporate->bkash_corporate_verified == -1)
                        <a href="#" id="{{ $unverified_bkash_corporate->id }}" class="label label-success" onclick="verifyBkashCorporate(this)">Verify</a>
                        <a href="{{ route('payment.bkash.corporate.dissaproved', $unverified_bkash_corporate->id) }}" class="label label-danger">Reject</a>
                        @elseif($unverified_bkash_corporate->bkash_corporate_verified == 1)
                        <p class="text-success text-weight-bold">Verified</p>
                        @elseif($unverified_bkash_corporate->bkash_corporate_verified == 0)
                        <p class="text-danger text-weight-bold">Unverified</p>
                        @endif
                      </td>

                      <td>{{ number_format($unverified_bkash_corporate->amount_paid) }}</td>
                    </tr>

              @endforeach

            </tbody>

            <tfoot>

               <tr>
                  <th>Date</th>
                  <th>Client Code</th>
                  <th>Client Name</th>
                  <th>Program Name</th>
                  <th>Step Name</th>
                  <th>Counselor</th>
                  <th>RM</th>
                  <th>Status</th>
                  <th>Amount</th>
               </tr>

            </tfoot>

         </table>

    </div>

  </div>

</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">bKash Information</h4>
      </div>
      <div class="modal-body">
        {{ Form::open(['route'=>'payment.update.bkash.corporate.info']) }}

        {{-- Hidden Fields --}}

        {{ Form::hidden('payment_id', null, ['id'=>'payment-id']) }}

        {{--  --}}

        <div class="form-group">

          {{ Form::label('Deposite Date') }}
          {{ Form::date('deposit_date', null, ['class'=>'form-control', 'id'=>'deposit-date']) }}
          
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

    {{ Form::close() }}

  </div>
</div>


<div id="verify" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Verify bKash Corporate</h4>
      </div>
      {{ Form::open(['route'=>'payment.bkash.corporate.verification']) }}
      <div class="modal-body">

        {{-- Hidden field --}}

        {{ Form::hidden('payment_id', null, ['id'=>'verify-payment-id']) }}

        {{-- End of hidden field --}}

          <div class="form-group">

            {{ Form::label('date_deposited') }}
            {{ Form::date('date_deposited', null, ['class'=>'form-control', 'required']) }}
            
          </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      {{ Form::close() }}
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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>

<script type="text/javascript">

  function verifyBkashCorporate(elem) {

    var payment_type_id = elem.id;
    
    document.getElementById('verify-payment-id').value = payment_type_id;

    $('#verify').modal();

  }

  
</script>
@endsection