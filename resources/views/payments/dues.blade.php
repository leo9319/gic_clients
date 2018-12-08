@extends('layouts.master')

@section('url', $previous)

@section('title', 'Dues')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#client_dues').DataTable({

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

			<h2 class="text-center">Client Dues</h2>

		</div>

		<div class="panel-footer">

			<table id="client_dues" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Clearance Date</th>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Total Amount</th>

                  <th>Total Paid</th>

                  <th>Due</th>

                  <th class="text-center">Action</th>

               </tr>

            </thead>

            <tfoot>

               <tr>

                  <th>Clearance Date</th>

                  <th>Client Name</th>

                  <th>Program Name</th>

                  <th>Step Name</th>

                  <th>Total Amount</th>

                  <th>Total Paid</th>

                  <th>Due</th>

                  <th class="text-center">Action</th>

               </tr>

            </tfoot>

            <tbody>
              @foreach($all_dues as $all_due)
            	<tr>
                <td>{{ Carbon\Carbon::parse($all_due->due_date)->format('d-M-y') }}</td>

                <td>{{ $all_due->userInfo->name }}</td>

                <td>{{ $all_due->programInfo->program_name }}</td>

                <td>{{ $all_due->stepInfo->step_name }}</td>

                <td>
                  {{ number_format($all_due->opening_fee + $all_due->embassy_student_fee + $all_due->service_solicitor_fee + $all_due->other) }}
                </td>

                <td>{{ number_format($all_due->totalPayment->sum('amount_paid')) }}</td>

                <td>{{ number_format($all_due->dues) }}</td>
                <td><a href="{{ route('payment.client.dues.details', $all_due->id) }}" class="btn btn-success btn-sm button2">Clear Due</a></td>
            	</tr>
              @endforeach
            </tbody>            

         </table>

		</div>

	</div>

</div>

@endsection