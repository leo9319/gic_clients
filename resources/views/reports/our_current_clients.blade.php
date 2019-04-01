@extends('layouts.master')

@section('title', 'Reports')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>



@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Our Current Clients</h2>

		</div>

		<div class="panel-footer">

			<table id="current_clients" class="table table-striped table-bordered" style="width:100%">

            <thead>

				<tr>

					<th>Date</th>
					<th>Location</th>
					<th>Client Code</th>
					<th>Name</th>
					<th>Phone</th>
					<th>Program</th>
					<th>Step</th>
					<th>Amount</th>
					<th>Step Payment Date</th>
					<th>Due</th>
					<th>Due Clear Date</th>
					<th>Counselor</th>
					<th>Rm</th>
					<th>Notes</th>

				</tr>

            </thead>

            <tbody>

            	@foreach($payments as $payment)

				<tr>
					<td>{{ Carbon\Carbon::parse($payment->created_at)->format('d-M-y') }}</td>
					<td>{{ ucfirst($payment->location) }}</td>
					<td>{{ $payment->userInfo->client_code }}</td>
					<td>{{ $payment->userInfo->name ?? 'Client Removed' }}</td>
					<td>{{ $payment->userInfo->mobile ?? 'Client Removed' }}</td>
					<td>{{ $payment->programInfo->program_name ?? 'Program Removed' }}</td>
					<td>{{ $payment->stepInfo->step_name ?? 'Step Removed' }}</td>
					<td>

						{{ 
							number_format(
								$payment->totalPayment->where('refund_payment', '!=', 1)->sum('amount_paid') -
								$payment->totalPayment->where('refund_payment', '=', 1)->sum('amount_paid')
						    )


						}}

					</td>
					<td>{{ Carbon\Carbon::parse($payment->created_at)->format('d-M-y') }}</td>
					<td>{{ number_format($payment->totalAmount() - $payment->totalVerifiedPayment->sum('amount_paid')) }}</td>
					<td>{{ Carbon\Carbon::parse($payment->due_date)->format('d-M-y') }}</td>
					<td>
						@if($payment->userInfo)

						@foreach($payment->userInfo->getAssignedCounselors as $counselor)
						{{ count($counselor->users) ? $counselor->user->name : 'User Deleted' }}
						@endforeach

						@else

						<p>User Removed</p>

						@endif
					</td>
					<td>
						@if($payment->userInfo)

						@foreach($payment->userInfo->getAssignedRms as $rm)
						{{ count($rm->users) ? $rm->user->name : 'User Deleted' }} 
						@endforeach	

						@else

						<p>User Removed</p>

						@endif
					</td>
					<td>{{$payment->comments}}</td>
				</tr>

				@endforeach

            </tbody>

            <tfoot>

               <tr>

                    <th>Date</th>
                    <th>Location</th>
                    <th>Client Code</th>
					<th>Name</th>
					<th>Phone</th>
					<th>Program</th>
					<th>Step</th>
					<th>Amount</th>
					<th>Step Payment Date</th>
					<th>Due</th>
					<th>Due Clear Date</th>
					<th>Counselor</th>
					<th>Rm</th>
					<th>Notes</th>

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

<script>

   $(document).ready( function () {

       $('#current_clients').DataTable({

       	dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]

       });

   });

</script>



@endsection

