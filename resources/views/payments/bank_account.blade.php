@extends('layouts.master')

@section('url', $previous)

@section('title', 'Bank Account')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#bank-account').DataTable({

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

			<h2>Bank Account</h2>

		</div>

		<div class="panel-footer">

      <a href="#" data-toggle="modal" data-target="#transferCash" class="btn btn-success pull-right button2" style="margin: 10px">Transfer cash</a>

			<table id="bank-account" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>SL.</th>

                  <th>Account Name</th>

                  <th>Received</th>

                  <th>Balance (Bank)</th>

                  <th class="text-center">Action</th>

               </tr>

            </thead>

            <tfoot>

               <tr>
                  <th>SL.</th>

                  <th>Account Name</th>

                  <th>Received</th>

                  <th>Balance (Bank)</th>

                  <th class="text-center">Action</th>

               </tr>

            </tfoot>

            <tbody>
            	@foreach($payment_details as $index => $payment_detail)
            	<tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ strtoupper($payment_detail->bank_name) }}</td>
            		<td>{{ number_format($payment_detail->total_amount, 2) }}</td>
                <td>{{ number_format($payment_detail->total, 2) }}</td>
            		<td>
            			<a href="{{ route('payment.account.detials', $payment_detail->bank_name) }}" class="btn btn-info btn-sm button2 btn-block">View Details</a>
            		</td>
            	</tr>
            	@endforeach
            </tbody>            

         </table>

		</div>

	</div>

</div>

<!-- Modal -->
<div id="transferCash" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Modal Header</h4>

      </div>

      <div class="modal-body">

        {{ Form::open(['route'=>'payment.account.transfer']) }}

          <div class="form-group">

            {{ Form::label('Amount:') }}
            {{ Form::text('amount', null, ['class'=>'form-control', 'required']) }}
            
          </div>

          <div class="form-group">

            {{ Form::label('Transfer To:') }}
            {{ Form::select('bank_name', $bank_accounts, null, ['class'=>'form-control']) }}
            
          </div>

      </div>

      <div class="modal-footer">

        <button type="submit" class="btn btn-success">Transfer</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        {{ Form::close() }}

      </div>

    </div>


  </div>
</div>

@endsection