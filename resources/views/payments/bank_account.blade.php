@extends('layouts.master')

@section('title', 'Bank Account')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>

   $(document).ready( function () {

       $('#bank-account').DataTable();

   });

   $(function(){
    $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  });

</script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Bank Account</h2>
      @if(Auth::user()->user_role == 'admin')
      <h5>Total Balance: {{ number_format(array_sum($banks)) }}</h5>
      @endif

		</div>

		<div class="panel-footer">

      @if(Auth::user()->user_role == 'admin')

      <a href="#" data-toggle="modal" data-target="#transferAmount" class="btn btn-success pull-right button2" style="margin: 10px">Transfer from Account</a>

      @endif

			<table id="bank-account" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>SL.</th>
                  <th>Account Name</th>
                  <th>Balance (Bank)</th>
                  <th class="text-center">Action</th>

               </tr>

            </thead>

            <tfoot>

               <tr>

                  <th>SL.</th>
                  <th>Account Name</th>
                  <th>Balance (Bank)</th>
                  <th class="text-center">Action</th>

               </tr>

            </tfoot>

            <tbody>

              @if(Auth::user()->user_role == 'accountant')

              <tr>
                <td>1</td>
                <td>Cash</td>
                <td>{{ number_format($banks['cash']) }}</td>
                <td>
                  <a href="{{ route('payment.account.detials', 'cash') }}" class="btn btn-info btn-sm button2 btn-block">View Details</a>
                </td>
              </tr>

              @else

              <?php $index = 1 ?>
            	@foreach($banks as $key => $value)
            	<tr>
                <td>{{ $index }}</td>
                <td>{{ strtoupper($key) }}</td>
            		<td>{{ number_format($value) }}</td>
            		<td>
            			<a href="{{ route('payment.account.detials', $key) }}" class="btn btn-info btn-sm button2 btn-block">View Details</a>
            		</td>
                <?php $index++ ?>
            	</tr>
            	@endforeach

              @endif

            </tbody>            

         </table>

		</div>

	</div>

</div>

<!-- Modal -->
<div id="transferAmount" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Transfer From Account</h4>

      </div>

      <div class="modal-body">

        {{ Form::open(['route'=>'payment.account.transfer']) }}

          <div class="form-group">
            {{ Form::label('Date:') }}
            {{ Form::date('date', Carbon\Carbon::now(), ['class'=>'form-control', 'required']) }}
          </div>

          <div class="form-group">

            {{ Form::label('Amount:') }}
            {{ Form::number('amount', null, ['class'=>'form-control', 'required']) }}
            
          </div>

          <div class="form-group">

            {{ Form::label('Description:') }}
            {{ Form::textarea('description', null, ['class'=>'form-control', 'rows'=> 3, 'required']) }}
            
          </div>

          <div class="form-group">

            {{ Form::label('Transfer From:') }}
            {{ Form::select('from_account', $bank_accounts, null, ['class'=>'form-control']) }}
            
          </div>

          <div class="form-group">

            {{ Form::label('Transfer To:') }}
            {{ Form::select('to_account', $bank_accounts, null, ['class'=>'form-control']) }}
            
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