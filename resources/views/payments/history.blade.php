@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'Payment History')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#payment-history').DataTable({

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

			<h2>Payment History</h2>

		</div>

		<div class="panel-footer">

			<table id="payment-history" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Client Code.</th>

                  <th>Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  <th>Invoice</th>

                  <th>Amount Paid</th>

               </tr>

            </thead>

            <tbody>

            	@foreach($payments as $payment)

                @foreach($payment->userInfo as $client)

                    @foreach($payment->programInfo as $program)

                  	<tr>
                      
                  		<td>
                        
                  			<a href="{{ route('client.profile', $payment->client_id) }}">
                          
                  				{{ $client->client_code }}
                          
                  			</a>
                        
                  		</td>
                      
                  		<td>{{ $client->name }}</td>
                      
                  		<td>{{ $program->program_name }}</td>
                      
                  		<td>{{ $payment->step_no }}</td>
                      
                  		<td>{{ number_format($payment->total_amount) }}</td>
                      
                  		<td>{{ number_format($payment->amount_paid) }}</td>
                      
                  	</tr>

                  @endforeach

                @endforeach

            	@endforeach

            </tbody>

            <tfoot>

               <tr>

                  <th>Client Code.</th>

                  <th>Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  <th>Invoice</th>

                  <th>Amount Paid</th>

               </tr>

            </tfoot>

         </table>

		</div>

	</div>

</div>

@endsection