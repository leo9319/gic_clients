{{-- Admin --}}

@extends('layouts.master')

@section('title', 'Income and Expense')

@section('content')

@section('header_scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
$(function() {

  var $tableSel = $('#example');
  $tableSel.dataTable({
    "order": [[ 6, "asc" ]],
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
                    this.rows().select();
                }
            },
            {
                text: 'Select none',
                action: function () {
                    this.rows().deselect();
                }
            }
        ],
        select: true
  });
  
  $('#filter').on('click', function(e){
    e.preventDefault();
    var startDate = $('#start').val(),
        endDate = $('#end').val();
    
    filterByDate(0, startDate, endDate); // We call our filter function
    
    $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
  });
  
  // Clear the filter. Unlike normal filters in Datatables,
  // custom filters need to be removed from the afnFiltering array.
  $('#clearFilter').on('click', function(e){
    e.preventDefault();
    $.fn.dataTableExt.afnFiltering.length = 0;
    $tableSel.dataTable().fnDraw();
  });
  
});

var filterByDate = function(column, startDate, endDate) {
  // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
          var rowDate = normalizeDate(aData[column]),
              start = normalizeDate(startDate),
              end = normalizeDate(endDate);

          
          // If our date from the row is between the start and end
          if (start <= rowDate && rowDate <= end) {
            return true;
          } else if (rowDate >= start && end === '' && start !== ''){
            return true;
          } else if (rowDate <= end && start === '' && end !== ''){
            return true;
          } else {
            return false;
          }
        }
    );
  };

  var normalizeDate = function(dateString) {
  var date = new Date(dateString);
  var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
  return normalized;
}
</script>


@stop

<div class="container-fluid">

  <div class="panel">

    <div class="panel-body">

      <h2>Advance Incomes</h2>

      <div class="pull-right">
        <a href="{{ route('payment.income.pdf') }}" class="btn btn-success btn-sm">View All Incomes</a>
        <a href="{{ route('payment.expense.pdf') }}" class="btn btn-danger btn-sm">View All Expenses</a>

        <a href="{{ route('payment.income.expense.pdf') }}" class="btn btn-info btn-sm">View Summary</a>
      </div>

    </div>

    <div class="panel-footer">

      <table border="0" cellspacing="5" cellpadding="5">
        <tbody>
          <tr>
            <td>Start Date: </td>
            <td><input type="date" id="start" name="min" class="form-control"></td>
          </tr>
          <tr>
              <td>End Date: </td>
              <td><input type="date" id="end" name="max" class="form-control"></td>
          </tr>
      </tbody>
    </table>

  <hr>

  <button id="filter" class="btn btn-success btn-sm">Filter</button>
  <button id="clearFilter" class="btn btn-info btn-sm" class="btn btn-success">Clear Filter</button>
  <hr>

      <table id="example" class="table table-striped table-bordered" style="width:100%">

            <thead>

               <tr>

                  <th>Date</th>

                  <th>Type</th>

                  <th>Advance Amount</th>

                  <th>Cleared Amount</th>

                  <th>Description</th>

                  <th>Deposited to</th>

                  <th>Location</th>

                  <th>Action</th>

                  <th>Verification</th>

                  <th>Action</th>

                  <th>Action</th>

               </tr>

            </thead>

            <tbody>

                  @foreach($transactions as $transaction)

                    <tr>

                      <td>{{ Carbon\Carbon::parse($transaction->created_at)->format('d-M-Y') }}</td>

                      <td>{{ ucfirst($transaction->payment_type) }}</td>

                      <td>{{ number_format(abs($transaction->total_amount)) }}</td>

                      <td>{{ number_format(abs($transaction->cleared_amount)) }}</td>

                      <td>{{ $transaction->description }}</td>

                      <td>{{ strtoupper($transaction->bank_name) }}</td>

                      <td>{{ ucfirst($transaction->location) }}</td>

                      <td><a href="#" id="{{ $transaction->id }}" onclick="clearAmount(this)" class="label label-info">Clear Amount</a></td>

                      @if($transaction->recheck == 0)

                        <td><p class="text-success"><b>Approved</b></p></td>

                      @elseif($transaction->recheck == -1)

                        <td><p class="text-danger"><b>Disapproved</b></p></td>

                      @else

                      <td>
                        <a href="{{ route('payment.recheck', [$transaction->id, 0]) }}" class="label label-success">Approve</a>

                        <a href="{{ route('payment.recheck', [$transaction->id, -1]) }}" class="label label-danger">Disapprove</a>
                      </td>

                      @endif


                      <td>
                        <a href="#" name="{{$transaction->id}}" onclick="editTransaction(this)"><i class="fa fa-edit"></i> Edit</a>
                      </td>

                      <td>
                        <a href="#" onclick="deleteTransaction(this)" id="{{ $transaction->id }}" class="btn btn-danger btn-sm button2">Delete</a>
                      </td>

                    </tr>

                  @endforeach

            </tbody>

            <tfoot>

               <tr>

                  <th>Date</th>

                  <th>Type</th>

                  <th>Advance Amount</th>

                  <th>Cleared Amount</th>

                  <th>Description</th>

                  <th>Deposited to</th>

                  <th>Location</th>

                  <th>Action</th>

                  <th>Verification</th>

                  <th>Action</th>

                  <th>Action</th>

               </tr>

            </tfoot>

         </table>

    </div>

  </div>

</div>

<!-- Modal -->
<div id="editTransaction" class="modal fade" role="dialog">

  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">

      <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal">&times;</button>

        <h4 class="modal-title">Edit Transaction</h4>

      </div>

      <div class="modal-body">

        {{ Form::open(['route' => 'payment.update.income.and.expenses']) }}

        {!! Form::hidden('type', null, ['id' => 'type']) !!}
        {!! Form::hidden('payment_id', null, ['id' => 'payment-id']) !!}

        <div class="form-group">

          {!! Form::label('Date:') !!}
        
          {!! Form::date('date', date('Y-m-d'), ['id' => 'date', 'class' => 'form-control']) !!}

        </div>

        <div class="form-group">

          {!! Form::label('Amount:') !!}
        
          {!! Form::number('amount', null, ['Placeholder'=>'Amount', 'id' => 'amount', 'class' => 'form-control', 'required']) !!}

        </div>

        <div class="form-group">

          {!! Form::label('Description:') !!}
        
          {!! Form::textarea('description', null, ['id' => 'description', 'class' => 'form-control']) !!}

        </div>

        <div class="form-group">

          {!! Form::label('Account Name:') !!}
        
          {!! Form::select('bank_name', $bank_accounts, null, ['id' => 'bank_name', 'class' => 'form-control']) !!}

        </div>

        <div class="form-group">

          {!! Form::label('Location:') !!}
        
          {!! Form::select('location', ['dhaka'=>'Dhaka', 'chittagong'=>'Chittagong'], null, ['id' => 'location', 'class' => 'form-control']) !!}

        </div>

        </div>

      <div class="modal-footer">

        <button type="submit" class="btn btn-info">Update</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        {{ Form::close() }}

      </div>

    </div>

  </div>

</div>

<div id="delete-transaction" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning!</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this entry?</p>
      </div>
      <div class="modal-footer">
        {{ Form::open(['route'=>'payment.delete.income.and.expenses']) }}

          {{ Form::hidden('id', null, ['id'=>'income-expense-id']) }}

          {{ Form::submit('Yes', ['class'=>'btn btn-danger']) }}   
        
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
        {{ Form::close() }}

      </div>
    </div>

  </div>
</div>

<!-- Modal -->
<div id="clear-amount" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Clear Amount</h4>
      </div>

      {{ Form::open(['route'=>'payment.update.advance.income.expense']) }}

      <div class="modal-body">

        {{-- Hidden field --}}

        {{ Form::hidden('transaction_id', null, ['id'=>'transaction-id']) }}

        {{-- End of hidden fields --}}
        
        <div class="form-group">

          {{ Form::label('select_option') }}
          {{ Form::select('select_option', ['full'=>'Clear full amount', 'partial'=>'Clear partial amount'], null, ['class'=>'form-control', 'id'=>'select-option']) }}
          
        </div>

        <div id="clear-amount-container"></div>

      </div>

      <div class="modal-footer">

        <button type="submit" class="btn btn-info">Clear</button>

        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

      </div>

      {{ Form::close() }}
    </div>

  </div>
</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

  $(function(){
    $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
  });

  function formatDate(date) {
      var d = new Date(date),
          month = '' + (d.getMonth() + 1),
          day = '' + d.getDate(),
          year = d.getFullYear();

      if (month.length < 2) month = '0' + month;
      if (day.length < 2) day = '0' + day;

      return [year, month, day].join('-');
  }
  
  function editTransaction(elem) {

    var id = elem.name;

    $.ajax({

        type: 'get',
        url: '{!!URL::to('findIncomeAndExpenses')!!}',
        data: {'id':id},

        success:function(data){

          document.getElementById('date').value = formatDate(data.created_at);
          document.getElementById('payment-id').value = data.id;
          document.getElementById('type').value = data.payment_type;
          document.getElementById("amount").value = Math.abs(data.total_amount);
          document.getElementById("description").innerHTML = data.description;
          document.getElementById("bank_name").value = data.bank_name;
          document.getElementById("location").value = data.location;
        },

        error:function(){
          alert('failure');
        }

      }); 


    $("#editTransaction").modal();

  }

  function deleteTransaction(elem) {
    var transaction_id = elem.id;

    document.getElementById('income-expense-id').value = transaction_id;

    $("#delete-transaction").modal();

  }

  function clearAmount(elem) {

      document.getElementById('transaction-id').value = elem.id;

      $('#clear-amount').modal();

  }
 
  $('#select-option').on('change', function() {
    
    var option = $('#select-option').find(":selected").val();
    $('#clear-amount-container').empty();

    if(option == 'partial') {

      var html = '<label>Total Amount To Be Cleared:</label> <br> <input id="cleared-amount" class="form-control"type="number" name="cleared_amount" required>';

      var transaction_id = $('#transaction-id').val();

      $.ajax({
        type: 'get',
        url: '{!!URL::to('findIncomeAndExpenses')!!}',
        data: {'id':transaction_id},
        success:function(data){
          $("#cleared-amount").attr('max', (data.total_amount));
        },
        error:function(){
          alert('failed to execute the command');
        }
      });

      $('#clear-amount-container').append(html);

    }

  });

</script>

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

@endsection