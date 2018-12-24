@extends('layouts.master')

@section('title', 'Unverified Cheques')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       var table = $('#unverified-cheques').DataTable({
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
                    table.rows().select();
                }
            },
            {
                text: 'Select none',
                action: function () {
                    table.rows().deselect();
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

			<h2>Unverified Cheques</h2>

		</div>

		<div class="panel-footer">

			<table id="unverified-cheques" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>
                  <th>Client Code</th>
                  <th>Client Name</th>
                  <th>Program Name</th>
                  <th>Step Name</th>
                  <th>Cheque Number</th>
                  <th>Deposited To</th>
                  <th>Deposit Date</th>
                  <th>Status</th>
                  <th>Edit</th>
               </tr>

            </thead>

            <tbody>

            	@foreach($unverified_cheques as $unverified_cheque)

                  	<tr>
                      <td>{{ $unverified_cheque->payment->userInfo->client_code ?? 'Client Removed' }}</td>
                      <td>{{ $unverified_cheque->payment->userInfo->name ?? 'Client Removed' }}</td>
                      <td>{{ $unverified_cheque->payment->programInfo->program_name ?? 'N/A' }}</td>
                      <td>{{ $unverified_cheque->payment->stepInfo->step_name ?? 'N/A' }}</td>
                      <td>{{ $unverified_cheque->cheque_number }}</td>
                      <td>{{ strtoupper($unverified_cheque->bank_name) }}</td>
                      <td>{{ Carbon\Carbon::parse($unverified_cheque->deposit_date)->format('d-M-y') }}</td>
                      <td>
                        @if($unverified_cheque->cheque_verified == -1)
                        <a href="{{ route('payment.cheque.verification', [$unverified_cheque->id, 1]) }}" class="label label-success">Verify</a>
                        <a href="{{ route('payment.cheque.verification', [$unverified_cheque->id, 0]) }}" class="label label-danger">Reject</a>
                        @elseif($unverified_cheque->cheque_verified == 1)
                        <p class="text-success text-weight-bold">Verified</p>
                        @elseif($unverified_cheque->cheque_verified == 0)
                        <p class="text-danger text-weight-bold">Unverified</p>
                        @endif
                      </td>

                      <td>
                        
                        {{-- <button type="button" class="btn btn-info button2 btn-sm" data-toggle="modal" data-target="#myModal">Edit</button> --}}

                        <button class="btn btn-info button2 btn-sm" onclick="editCheque(this)" id="{{ $unverified_cheque->id }}">Edit</button>

                      </td>
                    </tr>

            	@endforeach

            </tbody>

            <tfoot>

               <tr>
                  <th>Client Code</th>
                  <th>Client Name</th>
                  <th>Program Name</th>
                  <th>Step Name</th>
                  <th>Cheque Number</th>
                  <th>Deposited To</th>
                  <th>Deposit Date</th>
                  <th>Action</th>
                  <th>Edit</th>
               </tr>

            </tfoot>

         </table>

		</div>

	</div>

</div>


<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Cheque Information</h4>
      </div>
      <div class="modal-body">
        {{ Form::open(['route'=>'payment.update.cheque.info']) }}

        {{-- Hidden Fields --}}

        {{ Form::hidden('payment_id', null, ['id'=>'payment-id']) }}

        {{--  --}}

        <div class="form-group">

          {{ Form::label('Cheque_Number') }}
          {{ Form::text('cheque_number', null, ['class'=>'form-control', 'id'=>'cheque-number']) }}
          
        </div>

        <div class="form-group">

          {{ Form::label('Deposited To') }}
          {{ Form::select('bank_name', $data['bank_accounts'] = [
            'cash' => 'CASH',
            'scb' => 'SCB',
            'city' => 'CITY',
            'dbbl' => 'DBBL',
            'ebl' => 'EBL',
            'ucb' => 'UCB',
            'brac' => 'BRAC',
            'agrani' => 'AGRANI',
            'icb' => 'ICB',
            'salman account' => 'Salman Account',
            'kamran account' => 'Kamran Account'
         ], null, ['class'=>'form-control', 'id'=>'bank-name']) }}
          
        </div>

        <div class="form-group">

          {{ Form::label('Deposite Date') }}
          {{ Form::date('deposit_date', null, ['class'=>'form-control', 'id'=>'deposit-date']) }}
          
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

    {{ Form::close() }}

  </div>
</div>

@endsection

@section('footer_scripts')

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

<script type="text/javascript">

  function editCheque(elem){

    var payment_id = elem.id;

    $.ajax({
      type: 'get',
      url: '{!! URL::to('getChequeInfo') !!}',
      data: {'payment_id':payment_id},
      success:function(data) {

        document.getElementById('cheque-number').value = data.cheque_number;
        document.getElementById('bank-name').value = data.bank_name;
        document.getElementById('deposit-date').value = data.deposit_date;
        document.getElementById('payment-id').value = data.id;
      },
      error:function(){

      }

    });


    $('#myModal').modal();

  }

  
</script>
@endsection