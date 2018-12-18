@extends('layouts.master')

@section('title', 'Refund History')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#refund-history').DataTable();

   });

</script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Refund History</h2>

		</div>

		<div class="panel-footer">

			<table id="refund-history" class="table table-striped table-bordered" style="width:100%">

            <thead>
				<tr>
					<th>Date</th>
					<th>Client Name</th>
					<th>Program Name</th>
					<th>Step Name</th>
					<th>Payment Type</th>
					<th>Refunded From</th>
					<th>Cheque Number</th>
					<th>Amount Refunded</th>
					@if(Auth::user()->user_role == 'accountant')
					<th>Action</th>
					@endif
				</tr>
            </thead>

            <tbody>
            	@foreach($refunds as $refund)
            	<tr>
            		<td>{{ Carbon\Carbon::parse($refund->created_at)->format('d-M-y') }}</td>
            		<td>{{ $refund->payment->userInfo->name }}</td>
            		<td>{{ $refund->payment->programInfo->program_name }}</td>
            		<td>{{ $refund->payment->stepInfo->step_name }}</td>
            		<td>{{ ucfirst($refund->payment_type) }}</td>
            		<td>{{ strtoupper($refund->bank_name) }}</td>
            		<td>{{ $refund->cheque_number }}</td>
            		<td>{{ number_format($refund->amount_paid) }}</td>
            		@if(Auth::user()->user_role == 'accountant')
					<td>
						<a href="{{ route('payment.client.refund.delete', $refund->id) }}" class="btn btn-danger btn-sm button2">Delete</a>
					</td>
					@endif	
              	</tr>
              	@endforeach
              </tbody>

            <tfoot>
            	<tr>
					<th>Date</th>
					<th>Client Name</th>
					<th>Program Name</th>
					<th>Step Name</th>
					<th>Payment Type</th>
					<th>Refunded From</th>
					<th>Cheque Number</th>
					<th>Amount Refunded</th>
					@if(Auth::user()->user_role == 'accountant')
					<th>Action</th>
					@endif
				</tr>
            </tfoot>
         </table>

		</div>

	</div>

</div>

@endsection