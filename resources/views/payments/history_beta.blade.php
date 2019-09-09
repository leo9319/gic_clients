@extends('layouts.master')
@section('title', 'Payment History')
@section('content')
@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

@stop

<div class="container-fluid">
  <div class="panel">
    <div class="panel-body">
      <h2>Payment History</h2>
    </div>

    <div class="panel-footer">

      {{-- date filter --}}

          <div class="row">

            <div class="form-group col-md-6">
                <h5>Start Date <span class="text-danger"></span></h5>

                <div class="controls">
                    <input type="date" name="start_date" id="start_date" class="form-control datepicker-autoclose" placeholder="Please select start date">
                    <div class="help-block"></div>
                </div>
            </div>

            <div class="form-group col-md-6">
                <h5>End Date <span class="text-danger"></span></h5>
                <div class="controls">
                    <input type="date" name="end_date" id="end_date" class="form-control datepicker-autoclose" placeholder="Please select end date">
                    <div class="help-block"></div>
                </div>
            </div>

            <div class="text-left" style="margin-left: 15px;">
                <button type="text" id="btnFiterSubmitSearch" class="btn btn-info">Filter</button>
                <button type="text" id="btnResetFilter" class="btn btn-success">Reset Filter</button>
            </div>

          </div>

      {{-- end of date filter --}}

      <hr>

      <table class="table table-bordered table-striped" id="user_table">
          <thead>
              <tr>
                  <th width="10%">Date</th>
                  <th width="10%">Location</th>
                  <th width="10%">Receipt ID</th>
                  <th width="10%">Client Code.</th>
                  <th width="10%">Name</th>
                  <th width="20%">Program</th>
                  <th width="30%">Step</th>
                  <th width="20%">Counselors</th>
                  <th width="20%">RMs</th>
                  <th width="20%">Invoice Amount</th>
                  <th width="20%">Amount Paid</th>
                  <th width="20%">Due Amount</th>
                  <th width="20%">Comments</th>
                  <th width="20%">Action</th>
                  <th width="20%">View Details</th>
              </tr>
          </thead>
      </table>

    </div>
  </div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this payment?</p>
        {{ Form::open(['route' => 'payment.delete']) }}

          {{ Form::hidden('payment_id', null, ['id'=>'payment-id']) }}
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-default">Yes</button>
        {{ Form::close() }}
        <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
      </div>
    </div>

  </div>
</div>


@section('footer_scripts')

<script>
    $(document).ready(function() {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var $tableSel = $('#user_table');

        $tableSel.DataTable({
            lengthMenu: [
                [10, 25, 50, -1],
                ['10 rows', '25 rows', '50 rows', 'Show all']
            ],
            order: [ [0, 'desc'] ],
            dom: 'lBfrtip',
            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ],
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('payment.history.data') }}",
                type: 'GET',
                data: function (d) {

                  d.start_date = $('#start_date').val();
                  d.end_date   = $('#end_date').val();

                }
            },
            columns: [{
                data: 'payment_date',
                name: 'payment_date',
                render: function(data) {
                    const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
                    let current_datetime = new Date(data)
                    let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear()
                    return formatted_date;
                },
            }, {
                data: 'location',
                name: 'location',
                render: function(data) {
                    return data.charAt(0).toUpperCase() + data.slice(1);
                },
            }, {
                data: 'receipt_id',
                name: 'receipt_id'
            }, {
                data: 'client_code',
                name: 'client_code'
            }, {
                data: 'name',
                name: 'name'
            }, {
                data: 'program_name',
                name: 'program_name'
            }, {
                data: 'step_name',
                name: 'step_name'
            }, {
                data: 'counselors',
                name: 'counselors'
            }, {
                data: 'rms',
                name: 'rms'
            }, {
                data: 'invoice_amount',
                name: 'invoice_amount',
                render: function(data) {
                  return data;
                  // return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            }, {
                data: 'amount_paid',
                name: 'amount_paid',
                render: function(data) {
                  return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            }, {
                data: 'due_amount',
                name: 'due_amount',
                render: function(data) {
                  return data.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }
            }, {
                data: 'comments',
                name: 'comments'
            }, {
                data: 'action',
                name: 'action',
                orderable: false
            }, {
                data: 'view_details',
                name: 'view_details',
                orderable: false
            }, ]
        });

        // Upon click of a search button
        $('#btnFiterSubmitSearch').click(function() {
            $('#user_table').DataTable().draw(true);
        });

        // Upon click of a search button
        $('#btnResetFilter').click(function() {
            $('#start_date').val("");
            $('#end_date').val("");
            $('#user_table').DataTable().draw(true);
        });

        // Delete a payment data
        $(document).on('click', '.delete', function() {
            $('#payment-id').val($(this).attr('id'));
            $('#myModal').modal('show');
        });

    });
</script>

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

  function deletePayment(elem){
    document.getElementById('payment-id').value = elem.id
    $('#myModal').modal();
  }
</script>
@endsection

@endsection