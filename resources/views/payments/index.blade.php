@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'Payments')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

@endsection

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Receive Payment</h2>

		</div>

		<div class="panel-footer">

			{{ Form::open() }}

			<label>Choose Client:</label>

			<select class="select2 form-control" name="client_id" onchange="checkClientProgram(this)">

				@foreach($clients as $client)

					<option value="{{ $client->id }}">{{ $client->name }}</option>

				@endforeach

			</select>

			<br><br>

			<label>Select Program:</label>

			<select class="select2 form-control" name="program_id" id="programs" onchange="checkProgramStep(this)">

			</select>

			<br><br>

			<label>Select Step:</label>

			<select class="select2 form-control" name="step_no" id="step-number">

			</select>

			<br><br>

			<label>Payment Type:</label>

			<select class="select2 form-control" name="payment_type" onchange="addPaymentOptions(this)">

				<option value="cash">Cash</option>
				<option value="card">Card</option>
				<option value="emi">EMI</option>
				<option value="cheque">Cheque</option>
				<option value="bkash">bKash</option>
				<option value="upay">Upay</option>
				<option value="online">Online</option>

			</select>

			<br><br>

			<div id="payment-container"></div>

			<label>File opening fee</label>

			<input type="number" class="form-control" placeholder="File Opening Fee" name="opening_fee" required="required">

			<br>

			<label>Embassy/Student fee</label>

			<input type="number" class="form-control" placeholder="Embassy/Student fee" name="embassy_student_fee">

			<br>

			<label>Service / Solicitor Charge</label>

			<input type="number" class="form-control" placeholder="Service / Solicitor Charge" name="service_solicitor_fee">

			<br>

			<label>Other fee</label>

			<input type="number" class="form-control" placeholder="Other Fee" name="other">

			<br>

			<label>Amount Paid</label>

			<input type="number" class="form-control" placeholder="Amount Paid" name="amount_paid" required="required">

			<br>

			<input class="btn btn-primary btn-block" type="submit" name="">

			{{ Form::close() }}

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

	function addPaymentOptions(elem) {
		if (elem.value == 'card') {

			var html = '<label>Card Type:</label> <select class="select2 form-control" name="card_type" onchange="addCardCharge(this)"> <option value="visa">Visa</option> <option value="amex">Amex</option> <option value="master">Master</option> <option value="other">Other</option> </select> <br> <div id="other-card-container"></div> <label>Name on card:</label> <input type="text" name="name_on_card" placeholder="Name on card" class="form-control"> <br> <label>Card Number:</label> <input type="number" name="card_number" placeholder="Card Number" class="form-control"> <br> <label>Expiry Date</label> <input type="text" name="expiry_date" placeholder="Expiry Date" class="form-control"> <br>';

			$('#payment-container').empty();
        	$('#payment-container').append(html);

		} else if(elem.value == 'emi'){

			var html = '<label>Bank Name:</label> <input type="text" name="bank_name" placeholder="Bank Name" class="form-control"> <br> <label>Payment Type:</label> <select class="select2 form-control" name="car_name" onchange="addOtherCard(this)"> <option value="visa">Visa</option> <option value="amex">Amex</option> <option value="master">Master</option> <option value="other">Other</option> </select> <br> <div id="other-card-container2"></div> <label>Name on card:</label> <input type="text" name="name_on_card" placeholder="Name on card" class="form-control"> <br> <label>Card Number:</label> <input type="text" name="card_number" placeholder="Card Number" class="form-control"> <br> <label>Expiry Date</label> <input type="text" name="expiry_date" placeholder="Expiry Date" class="form-control"> <br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'cheque'){

			var html = '<label>Bank Name</label> <input type="text" name="bank_name" placeholder="Bank Name" class="form-control"> <br> <label>Cheque Number</label> <input type="text" name="cheque_number" placeholder="Cheque Number" class="form-control"> <br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'bkash' || elem.value == 'upay') {

			var html = '<label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control"> <br> ';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else {

			$('#payment-container').empty();
		}
	}

	function addCardCharge(elem) {

		if (elem.value == 'other') {

			var html = '<label>Card Name</label> <input type="text" name="card_type" placeholder="Card Name" class="form-control"> <br> ';

			$('#other-card-container').empty();
			$('#other-card-container').append(html);

		} else {
			$('#other-card-container').empty();
		}

	}

	function addOtherCard(elem) {

		if (elem.value == 'other') {

			var html = '<label>Card Name</label> <input type="text" name="card_type" placeholder="Card Name" class="form-control"> <br> ';

			$('#other-card-container2').empty();
			$('#other-card-container2').append(html);

		} else {

			$('#other-card-container2').empty();
		}

		

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

			error:function() {

			}

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

					option += '<option value="'+data[i].order+'">'+data[i].step_name+'</option>';

				}

				document.getElementById('step-number').innerHTML = option;

			},

			error:function() {

			}

		});

	}
	
	$(document).ready(function() {
		
		$(".select2").select2({
              placeholder: 'Select a value', 
              allowClear: true
            });
		
	});
	
</script>

@endsection