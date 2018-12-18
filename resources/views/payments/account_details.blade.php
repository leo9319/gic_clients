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

        ],
        "order": [[ 7, "asc" ]]

       });

       $('#incomes-expenses').DataTable({

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

      <p>Total amount : <b>{{ number_format($total_amount) }}</b></p>

		</div>

		<div class="panel-footer">

      <table id="account-details" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Date</th>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Recieved</th>

                  <th>Bank Charge</th>

                  <th>Amount after Charge</th>

                  <th>Balance</th>

                  <th class="text-center">Action</th>

               </tr>

            </thead>

            <tfoot>

               <tr>

                  <th>Date</th>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Recieved</th>

                  <th>Bank Charge</th>

                  <th>Amount after Charge</th>

                  <th>Balance</th>

                  <th class="text-center">Action</th>

               </tr>

            </tfoot>

            <tbody>
              <?php $sum = 0; ?>
              @foreach($payment_histories as $payment_history)
              <tr>
                <td>{{ Carbon\Carbon::parse($payment_history->created_at)->format('d-m-y') }}</td>
                <td>{{ $payment_history->payment->userInfo->name ?? 'Client Removed' }}</td>
                <td>{{ $payment_history->payment->programInfo->program_name }}</td>
                <td>{{ $payment_history->payment->stepInfo->step_name }}</td>
                <td>{{ number_format($payment_history->amount_paid) }}</td>
                <td>{{ $payment_history->bank_charge }} %</td>
                <td>{{ number_format($payment_history->amount_received) }}</td>
                <td>{{ number_format($sum = $sum + $payment_history->amount_received) }}</td>
                <td>
                  <a href="{{ route('payment.show', $payment_history->payment->id) }}" class="btn btn-info btn-sm btn-block button2">View Details</a>
                </td>
              </tr>
              @endforeach
            </tbody>            

         </table> 

    </div>

  </div>

  <div class="panel">

    <div class="panel-body">

      <h2 class="text-center">Income and Expenses</h2>
      
    </div>

    <div class="panel-footer">

			<table id="incomes-expenses" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Date</th>

                  <th>Type</th>

                  <th>Description</th>

                  <th>Amount</th>

               </tr>

            </thead>

            <tfoot>

               <tr>

                  <th>Date</th>

                  <th>Type</th>

                  <th>Description</th>

                  <th>Amount</th>

               </tr>

            </tfoot>

            <tbody>
              @foreach($incomes_and_expenses as $income_expense)
            	<tr>
                <td>{{ Carbon\Carbon::parse($income_expense->created_at)->format('d-M-y') }}</td>
                <td>{{ ucfirst($income_expense->payment_type) }}</td>
                <td>{{ $income_expense->description }}</td>
                <td>{{ number_format(abs($income_expense->total_amount)) }}</td>
            	</tr>
              @endforeach
            </tbody>            

         </table>



		</div>

  </div>

	</div>

</div>

@endsection