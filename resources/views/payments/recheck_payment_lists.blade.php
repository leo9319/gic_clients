@extends('layouts.master')

@section('title', 'Recheck Payments')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#recheck-payments').DataTable({

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

   });

</script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Recheck Payments</h2>

		</div>

		<div class="panel-footer">

			<table id="recheck-payments" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>
                <th>ID</th>
               	<th>Date</th>
               	<th>Client Code</th>
               	<th>Client Name</th>
               	<th>Payment Type</th>
               	<th>Deposited To</th>
               	<th>Amount Paid</th>
               	<th>Bank Charge</th>
               	<th>Amount Received</th>
               	<th>Action</th>
               </tr>

            </thead>

            <tbody>

            	@foreach($payments_types as $payments_type)

            	<tr>
                <td>{{ $payments_type->id }}</td>
            		<td>{{ Carbon\Carbon::parse($payments_type->created_at)->format('d-M-y') }}</td>
            		<td>{{ $payments_type->payment->userInfo->client_code ?? 'Client Removed' }}</td>
            		<td>{{ $payments_type->payment->userInfo->name ?? 'Client Removed' }}</td>
            		<td>{{ ucfirst($payments_type->payment_type) }}</td>
            		<td>{{ strtoupper($payments_type->bank_name) }}</td>
            		<td>{{ number_format($payments_type->amount_paid) }}</td>
            		<td>{{ $payments_type->bank_charge }}%</td>
            		<td>{{ number_format($payments_type->amount_paid) }}</td>
            		<td>
            			<a href="{{ route('payment.client.edit.types.list', $payments_type->id) }}"><i class="fa fa-edit"></i></a>
            			<a href="{{ route('payment.structure.client', [$payments_type->id, $payments_type->payment_type]) }}"><i class="fa fa-search-plus"></i></a>
            		</td>
            	</tr>

            	@endforeach

            </tbody>

            <tfoot>

               <tr>
                <th>ID</th>
               	<th>Date</th>
               	<th>Client Code</th>
               	<th>Client Name</th>
               	<th>Payment Type</th>
               	<th>Deposited To</th>
               	<th>Amount Paid</th>
               	<th>Bank Charge</th>
               	<th>Amount Received</th>
               	<th>Action</th>
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