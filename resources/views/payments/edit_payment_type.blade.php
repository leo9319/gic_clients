@extends('layouts.master')

@section('title', 'Edit Payment Type')

@section('content')

@section('header_scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

@endsection

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body" style="margin: 15px">

			<span class="h2">Edit Payment Type</span>

			<span><button type="button" class="btn btn-danger btn-sm button2 pull-right" data-toggle="modal" data-target="#myModal">Delete and Reissue</button></span>
			

		</div>

		<div class="panel-footer">

			{{ Form::model($payment_type, ['route' => ['payment.client.update.type'], 'autocomplete'=>'off']) }}

			{{-- Hidden fields --}}

				{{ Form::hidden('id') }}
				{{ Form::hidden('bank_name') }}
				{{ Form::hidden('payment_id') }}
				{{ Form::hidden('bank_charge') }}

			{{-- End of hidden fields --}}

			<div class="form-group">

				{{ Form::label('Date') }}
				{{ Form::date('date', $payment_type->created_at, ['class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Payment Type') }}
				{{ Form::text('payment_type', null, ['placeholder'=>'Payment Type', 'class'=>'form-control', 'readonly']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Card Type') }}
				{{ Form::text('card_type', null, ['placeholder'=>'Card Type', 'class'=>'form-control', 'readonly']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Name on Card') }}
				{{ Form::text('name_on_card', null, ['placeholder'=>'Name on card', 'class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Card Number') }}
				{{ Form::text('card_number', null, ['placeholder'=>'Card Number', 'class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Expiry Date') }}
				{{ Form::text('expiry_date', null, ['placeholder'=>'Expiry Date', 'class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('POS Machine') }}
				{{ Form::text('pos_machine', null, ['placeholder'=>'POS Machine', 'class'=>'form-control', 'readonly']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Approval Code') }}
				{{ Form::text('approval_code', null, ['placeholder'=>'Approval Code', 'class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Cheque Number') }}
				{{ Form::text('cheque_number', null, ['placeholder'=>'Cheque Number', 'class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Phone Number') }}
				{{ Form::text('phone_number', null, ['placeholder'=>'Phone Number', 'class'=>'form-control']) }}
				
			</div>

			<div class="form-group">

				{{ Form::label('Amount Paid') }}
				{{ Form::text('amount_paid', null, ['placeholder'=>'amount_paid', 'class'=>'form-control']) }}
				
			</div>

			<br>

			<div class="form-group">

				{{ Form::submit('Update', ['class'=>'btn btn-info btn-block button2']) }}
				
			</div>

			{{ Form::close() }}

			
		</div>

	</div>

</div>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Payment Type</h4>
      </div>
      <div class="modal-body">
        {{ Form::open(['route'=>'payment.client.delete.and.reissue']) }}


        <div class="form">

        	<label class="text-success">Payment Type:</label>

        		{{ Form::hidden('id', $payment_type->id) }}
        		{{ Form::hidden('payment_id', $payment_type->payment_id) }}
        		{{ Form::hidden('due_payment', $payment_type->due_payment) }}

				<select class="form-control" name="payment_type" onchange="addPaymentOptions(this)" required>
					<option value="">Select a payment method</option>
					<option value="cash">Cash</option>
					<option value="card">POS</option>
					<option value="cheque">Cheque</option>
					<option value="bkash_corporate">bKash - Corporate</option>
					<option value="bkash_salman">bKash - Salman</option>
					<option value="upay">Upay</option>
					<option value="online">Online</option>
				</select>

				<br>

				<div id="payment-container"></div>
        	
        </div>

        
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      {{ Form::close() }}
    </div>

  </div>
</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">
	
function addPaymentOptions(elem) {

	if (elem.value == 'card') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <label>Card Type:</label> <select class="select2 form-control" name="card_type" onchange="getPOSMachine(this)"> <option value="visa">Visa</option> <option value="master">Master</option> <option value="amex">Amex</option> <option value="nexus">Nexus</option> </select> <br> <div id="other-card-container"></div> <label>Name on card:</label> <input type="text" name="name_on_card" placeholder="Name on card" class="form-control" required> <br> <label>Card Number (Last 4 Digits Only):</label> <input type="text" name="card_number" maxlength="4" placeholder="Card Number" class="form-control" required> <br> <label>Expiry Date:</label> <input type="text" name="expiry_date" placeholder="Expiry Date" class="form-control"> <br> <label>Select Card/POS Machine:</label> <select class="select2 form-control" name="pos_machine_mod" id="pos-machine-mod" onchange="checkForCityBank(this)"> <option value="brac">BRAC</option> <option value="city">City</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="dbbl">DBBL</option> </select> <br> <div id="alternate_pos_machine"></div> <div id="bank-card"></div> <label>Approval Code:</label> <input type="text" name="approval_code" id="approval_code" placeholder="Approval Code" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid through POS" name="total_amount" onchange="getTotalAmount(this)" required></div> <br>';

		$('#payment-container').empty();
    	$('#payment-container').append(html);

	} else if (elem.value == 'cash') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <div class="form-group"><input type="hidden" name="bank_name" value="cash"><input type="number" class="total form-control" placeholder="Amount paid in cash" name="total_amount" onchange="getTotalAmount(this)" required></div>';

		$('#payment-container').empty();
		$('#payment-container').append(html);

	} else if (elem.value == 'cheque') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <label>Cheque Deposited To:</label> <select class="select2 form-control" name="bank_name" onchange="addBankName(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Cheque Number:</label> <input type="text" name="cheque_number" placeholder="Cheque Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid in cheque" name="total_amount" onchange="getTotalAmount(this)" required></div> <br>';

		$('#payment-container').empty();
		$('#payment-container').append(html);

	} else if (elem.value == 'bkash_corporate') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="scb" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount" onchange="getTotalAmount(this)" required></div> <br>';

		$('#payment-container').empty();
		$('#payment-container').append(html);

	} else if (elem.value == 'bkash_salman') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="salman account" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount" onchange="getTotalAmount(this)" required></div> <br>';

		$('#payment-container').empty();
		$('#payment-container').append(html);

	} else if (elem.value == 'upay') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="ucb" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid through upay" name="total_amount" onchange="getTotalAmount(this)" required></div> <br> ';

		$('#payment-container').empty();
		$('#payment-container').append(html);

	} else if(elem.value == 'online') {

		var html = '<div class="form-group"> <label>Date:</label> <input type="date" name="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}"> </div> <label>Select Bank:</label> <select class="select2 form-control" name="bank_name" onchange="addCardCharge(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid online" name="total_amount" onchange="getTotalAmount(this)" required> </div> <br>';

		$('#payment-container').empty();
		$('#payment-container').append(html);

	} else {

		$('#payment-container').empty();

	}

}

function getPOSMachine(elem) {
	var card_type = elem.value;
	var option = '';
	var elementTagForPosMachine = 'pos-machine-mod';

	if (card_type == 'amex') {
        objSelect = document.getElementById(elementTagForPosMachine)
        setSelectedValue(objSelect, "City");

        document.getElementById(elementTagForPosMachine).disabled = true;

        document.getElementById('alternate_pos_machine').innerHTML += '<input type="hidden" name="pos_machine_mod" value="city">';
		
	} else if (card_type == 'nexus') {
        objSelect = document.getElementById(elementTagForPosMachine)
        setSelectedValue(objSelect, "DBBL");

        document.getElementById(elementTagForPosMachine).disabled = true;

        document.getElementById('alternate_pos_machine').innerHTML += '<input type="hidden" name="pos_machine_mod" value="dbbl">';
		
	} else {
		setSelectedValue(objSelect, "BRAC");
		document.getElementById(elementTagForPosMachine).disabled = false;
	}
}

function setSelectedValue(selectObj, valueToSet) {

    for (var i = 0; i < selectObj.options.length; i++) {
        if (selectObj.options[i].text== valueToSet) {
            selectObj.options[i].selected = true;
            return;
        }
    }
}

function checkForCityBank(elem) {

	var pos_machine = elem.value;

	if (pos_machine == 'city') {

		var html = '<label>Is it a City Banks card?</label> <select class="form-control" name="city_bank"> <option value="yes">Yes</option> <option value="no">No</option> </select> <br>';

		$('#bank-card').append(html);

	} else {

		$('#bank-card').empty();

	}
}

function deleteAndReissue(elem) {

	alert(elem.id);

}

$( function() {
	var today = new Date();

    $("#due-date").datepicker({
    	dateFormat: 'yy-mm-dd',
	    minDate: '0',
	});
});

$(document).ready(function() {
		
	$(".select2").select2({
		placeholder: 'Select a value', 
		allowClear: true
	});
	
});

</script>



@endsection

