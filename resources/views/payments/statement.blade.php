@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'Statement')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#statement').DataTable({

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

			<h2>Statement of Accounts</h2>

		</div>

		<div class="panel-footer">

			<table id="statement" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Client Code.</th>

                  <th>Name</th>

                  <th>Total Amount Paid</th>

                  <th>Total Due</th>

                  <th>Action</th>

               </tr>

            </thead>

            <tbody>
            	@foreach($clients as $client)
            	<tr>
            		<td>{{ $client->client_code }}</td>
            		<td>{{ $client->name }}</td>
            		<td>{{ number_format($client->totalAmount->sum('total_amount')) }}</td>
            		<td>{{ number_format($client->totalAmount->sum('total_amount') - $client->totalAmount->sum('amount_paid')) }}</td>
            		<td><a href="{{ route('payment.show.statement', $client->id) }}" class="btn btn-info button2">View Statement</a></td>
            	</tr>
            	@endforeach
            </tbody>

            <tfoot>

               <tr>

                  <th>Client Code.</th>

                  <th>Name</th>

                  <th>Total Amount Paid</th>

                  <th>Total Due</th>

                  <th>Action</th>

               </tr>

            </tfoot>

         </table>

		</div>

	</div>

</div>

@endsection