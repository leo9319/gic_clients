@extends('layouts.master')

@section('url', $previous)

@section('title', 'Payments')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

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

			<h2>Receive Payment</h2>

		</div>

		<div class="panel-footer">

			{{-- {{ Form::open(['autocomplete = off']) }} --}}
			{{ Form::open() }}

			<label>Client ID:</label>

			<select class="select2 form-control" name="client_code" onchange="checkClientInfo(this)">

				<option value="0">No Client Selected</option>

				@foreach($clients as $client)

					<option value="{{ $client->id }}">{{ $client->client_code }}</option>

				@endforeach

			</select>

			<br><br>

			<label>Choose Client:</label>

			<select class="select2 form-control" name="client_id" id="client-name" onchange="checkClientProgram(this)">

				<option value="0">No Client Selected</option>

			</select>

			<br><br>

			<label>Select Program:</label>

			<select class="select2 form-control" name="program_id" id="programs" onchange="checkProgramStep(this)">

			</select>

			<br><br>

			<label>Select Step:</label>

			<select class="select2 form-control" name="step_id" id="step-number">

			</select>

			<br><br>

			<label>Payment Type:</label>

			<select class="select2 form-control" name="payment_type" onchange="addPaymentOptions(this)">

				<option value="">Select a payment method</option>
				<option value="cash">Cash</option>
				<option value="card">POS</option>
				<option value="cheque">Cheque</option>
				<option value="bkash_corporate">bKash - Corporate</option>
				<option value="bkash_salman">bKash - Salman</option>
				<option value="upay">Upay</option>
				<option value="online">Online</option>

			</select>

			<br><br>

			<div id="payment-container"></div>

			<label>File opening fee:</label>

			<input type="number" id="file-opening-fee" value="0" class="form-control" placeholder="File Opening Fee" name="opening_fee" onkeyup="sumOfTotal()" required="required">

			<br>

			<label>Embassy/Student fee:</label>

			<input type="number" id="embassy-student-fee" value="0" class="form-control" placeholder="Embassy/Student fee" onkeyup="sumOfTotal()" name="embassy_student_fee">

			<br>

			<label>Service / Solicitor Charge:</label>

			<input type="number" id="service-solicitor-fee" value="0" class="form-control" placeholder="Service / Solicitor Charge" name="service_solicitor_fee" onkeyup="sumOfTotal()">

			<br>

			<label>Other fee:</label>

			<input type="number" id="other-fee" value="0" class="form-control" placeholder="Other Fee" name="other" onkeyup="sumOfTotal()">

			<br>

			<label>Total Amount:</label>

			<input type="number" id="total-amount" class="form-control" placeholder="Total Amount" name="total_amount">

			<br>

			<label>Amount Paid:</label>

			<input type="number" class="form-control" placeholder="Amount Paid" name="amount_paid" value="0" required="required">

			<br>

			<label>Due Clearance Date:</label>

			<input type="date" class="form-control" name="due_clearance_date">

			<br>
			<br>

			<input class="btn btn-primary btn-block button2" type="submit" name="">

			{{ Form::close() }}

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

	function addPaymentOptions(elem) {

		if (elem.value == 'card') {

			var html = '<label>Card Type:</label> <select class="select2 form-control" name="card_type" onchange="addCardCharge(this)"> <option value="visa">Visa</option> <option value="master">Master</option> <option value="amex">Amex</option> <option value="nexus">Nexus</option> <option value="other">Other</option> </select> <br> <div id="other-card-container"></div> <label>Name on card:</label> <input type="text" name="name_on_card" placeholder="Name on card" class="form-control" required> <br> <label>Card Number (Last 4 Digits Only):</label> <input type="text" name="card_number" maxlength="4" placeholder="Card Number" class="form-control" required> <br> <label>Expiry Date:</label> <input type="text" name="expiry_date" placeholder="Expiry Date" class="form-control"> <br> <label>Select Card/POS Machine:</label> <select class="select2 form-control" name="pos_machine" onchange="addBankName(this)"> <option value="brac">BRAC</option> <option value="city">City</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="dbbl">DBBL</option> </select> <br> <div id="bank-card"></div> <label>Approval Code:</label> <input type="text" name="approval_code" placeholder="Approval Code" class="form-control" required> <br>';

			$('#payment-container').empty();
        	$('#payment-container').append(html);

		} else if (elem.value == 'cash') {

			var html = '<input type="hidden" name="bank_name" value="cash">';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'cheque') {

			var html = '<label>Cheque Deposited To:</label> <select class="select2 form-control" name="bank_name" onchange="addBankName(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Cheque Number:</label> <input type="text" name="cheque_number" placeholder="Cheque Number" class="form-control"> <br> ';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'bkash_corporate') {

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="scb" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control"> <br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'bkash_salman') {

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="salman account" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control"> <br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'upay') {

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="ucb" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control"> <br> ';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if(elem.value == 'online') {

			var html = '<label>Select Bank:</label> <select class="select2 form-control" name="bank_name" onchange="addCardCharge(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select><br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else {

			$('#payment-container').empty();

		}
	}

	function addCardCharge(elem) {

		if (elem.value == 'other') {

			var html = '<label>Card Type</label> <input type="text" name="card_type" placeholder="Card Name" class="form-control"> <br> ';

			$('#other-card-container').empty();
			$('#other-card-container').append(html);

		} else {

			$('#other-card-container').empty();

		}

	}

	function addBankName(elem) {

		if (elem.value == 'city') {

			var html = '<label>Bank Card:</label> <select class="select2 form-control" name="bank_card"> <option value="city">City</option> <option value="other">Other</option> </select> <br>';

			$('#bank-card').empty();
			$('#bank-card').append(html);

		} else {

			$('#bank-card').empty();

		}

	}

	function addOtherCard(elem) {

		if (elem.value == 'other') {

			var html = '<label>Card Type</label> <input type="text" name="card_type" placeholder="Card Name" class="form-control"> <br> ';

			$('#other-card-container2').empty();
			$('#other-card-container2').append(html);

		} else {

			$('#other-card-container2').empty();

		}
	}

	function checkClientInfo(elem) {

		var client_id = elem.value;
		var client_name = '';
		var programs = '';

		$.ajax({

			type: 'get',
			url: '{!!URL::to('getClientName')!!}',
			data: {'client_id' : client_id},

			success:function(data) {

				client_name += '<option value="'+data.id+'">'+data.name+'</option>';
				document.getElementById('client-name').innerHTML = client_name;	

				// Now fetch the client programs and steps;

				var client_id = data.id;

				$.ajax({

					type: 'get',
					url: '{!!URL::to('getIndividualClientProgram')!!}',
					data: {'client_id':client_id},

					success:function(data) {

						if(data.length > 0) {

							programs += '<option value="0">Please select program</option>';

							for(var i = 0; i < data.length; i++) {

								programs += '<option value="'+data[i].program_id+'">'+data[i].program_name+'</option>';

							}

							document.getElementById('programs').innerHTML = programs;

						} else {

							programs += '<option value="0">Not Enrolled</option>';

							document.getElementById('programs').innerHTML = programs;
							document.getElementById('step-number').innerHTML = programs;

						}

					},

				});

			},

		});

	}

	function checkClientProgram(elem) {

		var programs;
		var client_id = elem.value;

		$.ajax({

			type: 'get',
			url: '{!!URL::to('getIndividualClientProgram')!!}',
			data: {'client_id':client_id},

			success:function(data) {

				for(var i = 0; i < data.length; i++) {

					programs += '<option value="'+data[i].program_id+'">'+data[i].program_name+'</option>';

				}

				document.getElementById('programs').innerHTML = programs;

			},

		});

	}

	function checkProgramStep(elem) {

		var option = '';
		var program_id = elem.value;

		$.ajax({

			type: 'get',
			url: '{!!URL::to('findProgramStep')!!}',
			data: {'program_id':program_id},

			success:function(data) {

				for(var i = 0; i < data.length; i++) {

					option += '<option value="'+data[i].id+'">'+data[i].step_name+'</option>';

				}

				document.getElementById('step-number').innerHTML = option;

			},

		});

	}

	function sumOfTotal() {
		var openingFee = document.getElementById("file-opening-fee").value;
		var embassyStudentFee = document.getElementById("embassy-student-fee").value;
		var serviceSolicitorFee = document.getElementById("service-solicitor-fee").value;
		var otherFee = document.getElementById("other-fee").value;

		var totalAmount =  parseInt(openingFee) + parseInt(embassyStudentFee) + parseInt(serviceSolicitorFee) + parseInt(otherFee);

		document.getElementById("total-amount").value = totalAmount;
	}

	
	$(document).ready(function() {
		
		$(".select2").select2({
			placeholder: 'Select a value', 
			allowClear: true
		});
		
	});

	
</script>

@endsection