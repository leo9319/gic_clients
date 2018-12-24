@extends('layouts.master')

@section('title', 'Due Payment History')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       var table = $('#due-payment-history').DataTable({

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
                    table.rows().select();
                }
            },
            {
                text: 'Select none',
                action: function () {
                    table.rows().deselect();
                }
            }
        ],
        select: true

       });

   });

</script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Due Payment History</h2>

		</div>

		<div class="panel-footer">

			<table id="due-payment-history" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Date</th>

                  <th>Client Code.</th>

                  <th>Client Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  <th>Due Paid</th>

                  <th>Remaining Due</th>

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
                <td>{{ number_format($due_payment->totalPayment->where('due_payment', 1)->sum('amount_paid')) }}</td>
                <td>{{ number_format($due_payment->dues) }}</td>
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

                  <th>Due Paid</th>

                  <th>Remaining Due</th>

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

@endsection