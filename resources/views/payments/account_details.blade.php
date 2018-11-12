@extends('layouts.master')

@section('url', $previous)

@section('title', 'Payment Details')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#account-details').DataTable({

       	'columnDefs' : [

       		{

       		}

       	]

       });

   });

</script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2 class="text-center">{{ strtoupper($account) }}</h2>

      <p>Total amount : <b>{{ number_format($payment_histories->sum('amount_paid')) }}</b></p>

		</div>

		<div class="panel-footer">

			<table id="account-details" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Payment Type</th>

                  <th>Recieved</th>

                  <th>Bank Charge</th>

                  <th>Amount after Charge</th>

                  <th class="text-center">Action</th>

               </tr>

            </thead>

            <tfoot>

               <tr>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Payment Type</th>

                  <th>Recieved</th>

                  <th>Bank Charge</th>

                  <th>Amount after Charge</th>

                  <th class="text-center">Action</th>

               </tr>

            </tfoot>

            <tbody>
              @foreach($payment_histories as $payment_history)
            	<tr>
                <td>
                  {{ App\User::find($payment_history->client_id) ?  App\User::find($payment_history->client_id)->name: 'N/A'}}
                </td>
                <td>
                  {{ App\Program::find($payment_history->program_id) ?  App\Program::find($payment_history->program_id)->program_name: 'N/A'}}
                </td>
                <td>
                  {{ App\Step::find($payment_history->step_id) ?  App\Step::find($payment_history->step_id)->step_name: 'N/A'}}
                </td>
                <td>{{ $payment_history->payment_type }}</td>
                <td>{{ number_format($payment_history->amount_paid) }}</td>
                <td>{{ $payment_history->bank_charges }}%</td>
                <td>{{ number_format($payment_history->total_after_charge,2) }}</td>
                <td>
                  <a href="{{ route('payment.show', $payment_history->id) }}" class="btn btn-info btn-sm btn-block button2">View Payment</a>
                </td>
            	</tr>
              @endforeach
            </tbody>            

         </table>

		</div>

	</div>

</div>

@endsection