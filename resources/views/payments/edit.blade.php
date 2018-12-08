@extends('layouts.master')

@section('url', $previous)

@section('title', 'Edit Payment')

@section('content')

@section('header_scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

			{{ Form::model($payment, ['route' => ['payment.update', $payment->id], 'autocomplete'=>'off']) }}

				{{ method_field('PUT') }}

				<div class="form-group">

					{{ Form::label('Receipt Number:') }}

					{{ Form::text('receipt_id', null , ['class'=>'form-control', 'readonly']) }}
					
				</div>

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

					{{ Form::text('program_id', $payment->programInfo->program_name, ['class'=>'form-control', 'readonly']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Client Step:') }}

					{{ Form::text('step_id', $payment->stepInfo->step_name, ['class'=>'form-control', 'readonly']) }}
					
				</div>

				<div id="payment-container"></div>

				<div class="form-group">

					<label>Initial Assessment fee:</label>

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

					<label>Due Clearance Date:</label>
					<input type="text" placeholder="Due Date" name="due_date" id="due-date" class="form-control" required="">

				</div>

				<br>

				<h2>Payment Structure</h2>
				<hr>

				@foreach($payment_types as $payment_type)
					@if($payment_type->payment_type == 'cash')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group">
							<label>Amount Paid:</label>

							<input type="text" class="form-control" name="amount_paid" value="{{ $payment_type->amount_paid }}">					
						</div>

						<hr>

					@elseif($payment_type->payment_type == 'card')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group">
						   <label>Card Type:</label> 
						   <input type="text" class="form-control" name="card_type" value="{{ $payment_type->card_type }}" readonly="">
						</div>

						<div class="form-group">
						   <label>Name on card:</label> 
						   <input type="text" name="name_on_card" placeholder="Name on card" class="form-control" value="{{ $payment_type->name_on_card }}"> 
						</div>

						<div class="form-group">
						   <label>Card Number (Last 4 Digits Only):</label>
						   <input type="text" name="card_number" maxlength="4" placeholder="Card Number" class="form-control" value="{{ $payment_type->card_number }}" required> 
						</div>

						<div class="form-group">
						   <label>Expiry Date:</label> 
						   <input type="text" name="expiry_date" placeholder="Expiry Date" value="{{ $payment_type->expiry_date }}" class="form-control"> 
						</div>

						<div class="form-group">
						   <label>Select Card/POS Machine:</label> 
						   <input type="text" name="pos_machine" class="form-control" value="{{ strtoupper($payment_type->pos_machine) }}" readonly="">
						</div>

						<div class="form-group">
						   <label>Approval Code:</label> 
						   <input type="text" name="approval_code" placeholder="Approval Code" class="form-control" value="{{ $payment_type->approval_code }}"> 
						</div>

						<div class="form-group">
						   <label>Total Amount:</label> 
						   <input type="number" class="total form-control" value="{{ $payment_type->amount_paid }}" placeholder="Amount paid through POS" name="total_amount" onchange="getTotalAmount(this)" required>
						</div>

						<hr>
						

					@elseif($payment_type->payment_type == 'cheque')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group">

							<label>Cheque Deposited To:</label> 
							<input type="text" name="bank_name" class="form-control" value="{{ strtoupper($payment_type->bank_name) }}" readonly="">

						</div>

						<div class="form-group">

							<label>Cheque Number:</label> 
							<input type="text" name="cheque_number" placeholder="Cheque Number" class="form-control" value="{{ $payment_type->cheque_number }}"> 

						</div>

						<div class="form-group">

							<label>Total Amount:</label> 
							<input type="number" class="form-control" placeholder="Amount paid in cheque" name="total_amount" value="{{ $payment_type->amount_paid }}">

						</div>

						<hr>
					

					@elseif($payment_type->payment_type == 'bkash_corporate')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group"> 
							<label>GIC Deposit Bank Name:</label> 
							<input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="scb" readonly> 
						</div>


						<div class="form-group"> 
							<label>Phone Number</label> 
							<input type="text" name="phone_number" placeholder="Phone Number" class="form-control" value="{{ $payment_type->phone_number }}" required> 
						</div>
						 

						<div class="form-group"> 
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount" value="{{ $payment_type->amount_paid }}">
						</div>

						<hr>

					@elseif($payment_type->payment_type == 'bkash_salman')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group">
							<label>GIC Deposit Bank Name:</label> 
							<input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="salman account" readonly>  
						</div>

						<div class="form-group">
							<label>Phone Number</label> 
							<input type="text" name="phone_number" placeholder="Phone Number" class="form-control" value="{{ $payment_type->phone_number }}"> 
						</div>
						 

						<div class="form-group">
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount" value="{{ $payment_type->amount_paid }}" required>
						</div>

						<hr>

					@elseif($payment_type->payment_type == 'upay')
						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group">
							<label>GIC Deposit Bank Name:</label> 
							<input type="text" name="bank_name" placeholder="GIC Deposit Bank Name" class="form-control" value="ucb" readonly> 
						</div>
						 

						<div class="form-group">
							<label>Phone Number</label> 
							<input type="text" name="phone_number" placeholder="Phone Number" class="form-control" value="{{ $payment_type->phone_number }}" required="">  
						</div>


						<div class="form-group">
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount" value="{{ $payment_type->amount_paid }}" required>
						</div>

						<hr>

					@elseif($payment_type->payment_type == 'online')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment_type" value="{{ ucfirst($payment_type->payment_type) }}" readonly="">
						</div>

						<div class="form-group">
							<label>Select Bank:</label> 
							<input type="text" name="bank_name" class="form-control" value="{{ strtoupper($payment_type->bank_name) }}" readonly="">
						</div>


						<div class="form-group">
							<label>Deposit Date:</label> 
							<input type="date" class="form-control" name="deposit_date" value="{{ $payment_type->deposit_date }}" required>
						</div>
						 

						<div class="form-group">
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="total_amount" value="{{ $payment_type->amount_paid }}" required>
						</div>
 

					</div>

					@endif


				@endforeach

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

	$( function() {
		var today = new Date();

	    $("#due-date").datepicker({
	    	dateFormat: 'yy-mm-dd',
		    minDate: '0',
		});
	});

</script>

@endsection

