@extends('layouts.master')

@section('url', $previous)

@section('title', 'Payment Type')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<style type="text/css">
	/* Hide HTML5 Up and Down arrows. */
input[type="number"]::-webkit-outer-spin-button, input[type="number"]::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}
 
input[type="number"] {
    -moz-appearance: textfield;
}
</style>

@endsection

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
@endif

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Payment Methods</h2>

		</div>

		<div class="panel-footer">

			{{ Form::open(['route'=>'payment.client.dues.payment.store', 'id' => 'myForm']) }}

			<input type="hidden" name="payment_id" value="{{ $payment_id }}">

			<div class="form-group">

				<label class="text-success">Payment Type:</label>

				<select id="0" class="form-control" name="payment_type-0" onchange="addPaymentOptions(this)" required="">

					<option value="">Select a payment method</option>
					<option value="cash">Cash</option>
					<option value="card">POS</option>
					<option value="cheque">Cheque</option>
					<option value="bkash_corporate">bKash - Corporate</option>
					<option value="bkash_salman">bKash - Salman</option>
					<option value="upay">Upay</option>
					<option value="online">Online</option>

				</select>
				
			</div>

			<div id="payment-container-0"></div><hr>

			<div class="form-group">

				<div id="payment-type"></div>

				<a href="javascript:void(0)" onclick="addPaymentType()">+ Add Payment Type</a>
				
			</div>

			<div class="form-group">

				<label>Total Due:</label>
				<input type="text" name="total_amount" class="form-control" id="total_amount" value="{{ $total_amount }}" disabled="disabled">

				<input type="hidden" name="total_amount" value="{{ $total_amount }}">
				
			</div>

			<div class="form-group">

				<label>Amount Paid:</label>
				<input type="text" name="amount_paid" id="amount-paid" class="form-control" value="0" disabled="disabled">
				<input type="hidden" name="amount_paid_active" id="amount-paid-active">
				
			</div>

			<button type="button" id="deliveryNext" onclick="myFunction()" class="btn btn-sm btn-success btn-block button2">Submit</button>


			{{ Form::close() }}

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">


	$(document).ready(function() {

		$("#deliveryNext").prop('disabled', true);
	     
	});

	var number = 1;

	function addPaymentType() {

		var html = '<div id="payment-div-#"><div class="form-group"> <label class="text-success">Payment Type:</label> <select id=# class="select2 form-control" name="payment_type-#" onchange="addPaymentOptions(this)" required> <option value="">Select a payment method</option> <option value="cash">Cash</option> <option value="card">POS</option> <option value="cheque">Cheque</option> <option value="bkash_corporate">bKash - Corporate</option> <option value="bkash_salman">bKash - Salman</option> <option value="upay">Upay</option> <option value="online">Online</option> </select> </div><div id="payment-container-#"></div><input type="hidden" name="counter" value="#"><a class="text-danger" href="javascript:void(0)" onclick="removePaymentType(#)">(-) Remove This Payment Type</a><hr></div>';

		html = html.replace(/#/g, number);

		$('#payment-type').append(html);
		number++;
		type++;

	}

	function removePaymentType(number) {
		var div_name = '#payment-div-' + number;

		$(div_name).remove();

	}

	function addPaymentOptions(elem) {

		if($("#deliveryNext").attr('disabled')) {
			$("#deliveryNext").prop('disabled', false);
		} 

		var number = elem.id;

		if (elem.value == 'card') {

			var html = '<label>Card Type:</label> <select class="select2 form-control" name="card_type-#" onchange="getPOSMachine(this)"> <option value="visa">Visa</option> <option value="master">Master</option> <option value="amex">Amex</option> <option value="nexus">Nexus</option> </select> <br> <div id="other-card-container"></div> <label>Name on card:</label> <input type="text" name="name_on_card-#" placeholder="Name on card" class="form-control" required> <br> <label>Card Number (Last 4 Digits Only):</label> <input type="text" name="card_number-#" maxlength="4" placeholder="Card Number" class="form-control" required> <br> <label>Expiry Date:</label> <input type="text" name="expiry_date-#" placeholder="Expiry Date" class="form-control"> <br> <label>Select Card/POS Machine:</label> <select class="select2 form-control" name="pos_machine-#" id="pos_machine-#" onchange="checkForCityBank(this)"> <option value="brac">BRAC</option> <option value="city">City</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="dbbl">DBBL</option> </select> <br> <div id="alternate_pos_machine-#"></div> <div id="bank-card-#"></div> <label>Approval Code:</label> <input type="text" name="approval_code-#" id="approval_code-#" placeholder="Approval Code" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid through POS" name="total_amount-#" onchange="getTotalAmount(this)" required></div> <br>';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
        	$('#payment-container-' + number).append(html);

		} else if (elem.value == 'cash') {

			var html = '<div class="form-group"><input type="hidden" name="bank_name-#" value="cash"><input type="number" class="total form-control" placeholder="Amount paid in cash" name="total_amount-#" onchange="getTotalAmount(this)" required></div>';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
			$('#payment-container-' + number).append(html);

		} else if (elem.value == 'cheque') {

			var html = '<label>Cheque Deposited To:</label> <select class="select2 form-control" name="bank_name-#" onchange="addBankName(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Cheque Number:</label> <input type="text" name="cheque_number-#" placeholder="Cheque Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid in cheque" name="total_amount-#" onchange="getTotalAmount(this)" required></div> <br>';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
			$('#payment-container-' + number).append(html);

		} else if (elem.value == 'bkash_corporate') {

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name-#" placeholder="GIC Deposit Bank Name" class="form-control" value="scb" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number-#" placeholder="Phone Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount-#" onchange="getTotalAmount(this)" required></div> <br>';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
			$('#payment-container-' + number).append(html);

		} else if (elem.value == 'bkash_salman') {

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name-#" placeholder="GIC Deposit Bank Name" class="form-control" value="salman account" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number-#" placeholder="Phone Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount-#" onchange="getTotalAmount(this)" required></div> <br>';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
			$('#payment-container-' + number).append(html);

		} else if (elem.value == 'upay') {

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name-#" placeholder="GIC Deposit Bank Name" class="form-control" value="ucb" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number-#" placeholder="Phone Number" class="form-control" required> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid through upay" name="total_amount-#" onchange="getTotalAmount(this)" required></div> <br> ';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
			$('#payment-container-' + number).append(html);

		} else if(elem.value == 'online') {

			var html = '<label>Select Bank:</label> <select class="select2 form-control" name="bank_name-#" onchange="addCardCharge(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Total Amount:</label> <input type="number" class="total form-control" placeholder="Amount paid online" name="total_amount-#" onchange="getTotalAmount(this)" required></div> <br>';

			html = html.replace(/#/g, number);

			$('#payment-container-' + number).empty();
			$('#payment-container-' + number).append(html);

		} else {

			$('#payment-container-' + number).empty();

		}

	}

	function getPOSMachine(elem) {
		var card_type = elem.value;
		var option = '';
		var number = parseInt(elem.name.replace(/[^0-9\.]/g, ''), 10);
		var elementTagForPosMachine = 'pos_machine-' + number;

		if (card_type == 'amex') {
            objSelect = document.getElementById(elementTagForPosMachine)
            setSelectedValue(objSelect, "City");

            document.getElementById(elementTagForPosMachine).disabled = true;

            document.getElementById('alternate_' + elementTagForPosMachine).innerHTML += '<input type="hidden" name="' + elementTagForPosMachine + '" value="city">';
			
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
		var number = parseInt(elem.name.replace(/[^0-9\.]/g, ''), 10);
		

		if (pos_machine == 'city') {

			var html = '<label>Is it a City Banks card?</label> <select class="form-control" name="city_bank-#"> <option value="yes">Yes</option> <option value="no">No</option> </select> <br>';

			html = html.replace(/#/g, number);
			$('#bank-card-' + number).append(html);

		} else {

			$('#bank-card-' + number).empty();

		}
	}

	function getTotalAmount(elem) {
		var classTags = document.getElementsByClassName('total');
		var total = 0;
		var i;

		for (i = 0; i < classTags.length; i++) {
		    total += parseInt(classTags[i].value);

		}

		document.getElementById('amount-paid').value = total;
		document.getElementById('amount-paid-active').value = total;

	}

	function myFunction() {
		var form = document.getElementById('myForm');

		for(var i=0; i < form.elements.length; i++){
	      if(form.elements[i].value === '' && form.elements[i].hasAttribute('required')){
	        alert('There are some required fields!');
	        form.elements[i].style.borderColor = "red";
	        return false;
	      } 
	    }

	    form.submit();

	    

	}

	$(document).ready(function() {
		
		$(".select2").select2({
			placeholder: 'Select a value', 
			allowClear: true
		});
		
	});

	
</script>

@endsection