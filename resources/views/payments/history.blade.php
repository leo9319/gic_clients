@extends('layouts.master')

@section('url', $previous)

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

                  <th>Date</th>

                  <th>Client Code.</th>

                  <th>Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  @if(Auth::user()->user_role == 'accountant')

                  <th>Action</th>

                  @endif

                  <th>View Details</th>

                  @if(Auth::user()->user_role == 'accountant')
                  <th>Edit</th>
                  @endif

               </tr>

            </thead>

            <tbody>

            	@foreach($payments as $payment)

                @foreach($payment->userInfo as $client)

                    @foreach($payment->programInfo as $program)

                  	<tr>

                      <td>{{ Carbon\Carbon::parse($client->created_at)->format('d-m-y') }}</td>
                      
                  		<td>
                        
                  			<a href="{{ route('client.profile', $payment->client_id) }}">
                          
                  				{{ $client->client_code }}
                          
                  			</a>
                        
                  		</td>
                      
                  		<td>{{ $client->name }}</td>
                      
                  		<td>{{ $program->program_name }}</td>
                      
                  		<td>

                        {{ App\Step::find($payment->step_id) ? App\Step::find($payment->step_id)->step_name : ''}}

                      </td>                     

                      @if(Auth::user()->user_role == 'accountant')

                      <td><a href="{{ route('payment.generate.invoice', $payment->id) }}" class="btn btn-info btn-sm button2">Generate Invoice</a></td>

                      @endif

                      <td><a href="{{ route('payment.show', $payment->id) }}" class="btn btn-defualt btn-sm button2">View Payment</a></td>

                      @if(Auth::user()->user_role == 'accountant')
                      <td>
                        <a href="{{ route('payment.edit', $payment->id) }}"><i class="fa fa-edit"></i></a>
                      </td>
                      @endif

                  	</tr>

                  @endforeach

                @endforeach

            	@endforeach

            </tbody>

            <tfoot>

               <tr>

                  <th>Date</th>

                  <th>Client Code.</th>

                  <th>Name</th>

                  <th>Program</th>

                  <th>Step</th>

                  @if(Auth::user()->user_role == 'accountant')

                  <th>Action</th>

                  @endif

                  <th>View Details</th>

                  @if(Auth::user()->user_role == 'accountant')
                  <th>Edit</th>
                  @endif

               </tr>

            </tfoot>

         </table>

		</div>

	</div>

</div>

@endsection