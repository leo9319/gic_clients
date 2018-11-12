@extends('layouts.master')

@section('url', $previous)

@section('title', 'Edit Payment')

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
    <div class="alert alert-info">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
@endif

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Edit Payment</h2>

		</div>

		<div class="panel-footer">

			{{ Form::model($payment, ['route' => ['payment.update', $payment->id]]) }}

				{{ method_field('PUT') }}

				<div class="form-group">

					{{ Form::label('Client Code:') }}

					{{ Form::text('client_code', App\User::find($payment->client_id)->client_code, ['class'=>'form-control', 'readonly']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Client Name:') }}

					{{ Form::text('client_name', App\User::find($payment->client_id)->name, ['class'=>'form-control', 'readonly']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Client Program:') }}

					{{ Form::select('program_id', $programs->pluck('program_name', 'id'), null, ['class'=>'form-control select2', 'onchange' => 'getSteps(this)']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Client Step:') }}

					{{ Form::select('step_id', $steps->pluck('step_name', 'id'), null, ['class'=>'form-control', 'id' => 'step-number']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Payment Type:') }}

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
					
				</div>

				<div id="payment-container"></div>

				<div class="form-group">

					<label>File opening fee:</label>

					{{ Form::number('opening_fee', null, ['class'=>'form-control', 'onkeyup'=>'sumOfTotal()', 'id' => 'file-opening-fee', 'required']) }}

				</div>

				<div class="form-group">

					<label>Embassy/Student fee:</label>

					{{ Form::number('embassy_student_fee', null, ['class'=>'form-control', 'id' => 'embassy-student-fee', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Service / Solicitor Charge:</label>

					{{ Form::number('service_solicitor_fee', null, ['class'=>'form-control', 'id'=>'service-solicitor-fee', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Other fee:</label>

					{{ Form::number('other', null, ['class'=>'form-control', 'id' => 'other-fee', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Total Amount:</label>

					{{ Form::number('total_amount', null, ['class'=>'form-control', 'id' => 'total-amount', 'required']) }}

				</div>

				<div class="form-group">

					<label>Amount Paid:</label>

					{{ Form::number('amount_paid', null, ['placeholder' => 'Amount Paid', 'class'=>'form-control', 'required']) }}

				</div>

				<div class="form-group">

					<label>Due Clearance Date:</label>

					{{ Form::date('due_clearance_date', null, ['class'=>'form-control']) }}

				</div>

				<br>

				<div class="form-group">
					{{ Form::submit('Update', ['class'=>'btn btn-primary btn-block button2']) }}
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

			var html = '<label>Card Type:</label> <select class="select2 form-control" name="card_type" onchange="addCardCharge(this)"> <option value="visa">Visa</option> <option value="master">Master</option> <option value="amex">Amex</option> <option value="nexus">Nexus</option> <option value="other">Other</option> </select> <br> <div id="other-card-container"></div> <label>Banks Card:</label> <select class="select2 form-control" name="banks_card"> <option value="other">Other</option> <option value="city">City Bank</option> </select> <br> <label>Name on card:</label> <input type="text" name="name_on_card" placeholder="Name on card" class="form-control" required> <br> <label>Card Number (Last 4 Digits Only):</label> <input type="text" name="card_number" maxlength="4" placeholder="Card Number" class="form-control" required> <br> <label>Expiry Date:</label> <input type="text" name="expiry_date" placeholder="Expiry Date" class="form-control"> <br> <label>Select Card/POS Machine:</label> <select class="select2 form-control" name="pos_machine" onchange="addBankName(this)"> <option value="city">City</option> <option value="brac">BRAC</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="dbbl">DBBL</option> </select> <br> <label>Approval Code:</label> <input type="text" name="approval_code" placeholder="Approval Code" class="form-control" required> <br>';

			$('#payment-container').empty();
        	$('#payment-container').append(html);

		} else if (elem.value == 'cash') {

			var html = '<label>Cash Deposited To:</label> <select class="select2 form-control" name="bank_name"> <option value="scb">SCB</option> <option value="city">City Bank</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br>';

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

			var html = '<label>GIC Deposit Bank Name:</label> <input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="salman bkash" readonly> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control"> <br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (elem.value == 'upay') {

			var html = '<label>GIC Deposit Bank Name:</label> <select class="select2 form-control" name="bank_name" onchange="addBankName(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Phone Number</label> <input type="text" name="phone_number" placeholder="Phone Number" class="form-control"> <br> ';

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
	
	function getSteps(elem) {

		var program_id = elem.value;
		var option = '';

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

