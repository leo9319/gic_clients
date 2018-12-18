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

			<h2>Refund Client</h2>

		</div>

		<div class="panel-footer">

			{{-- {{ Form::open(['autocomplete = off']) }} --}}
			{{ Form::open(['route'=>'payment.store.client.refund', 'id' => 'myForm']) }}

			{{-- Hiddin fields --}}

			<input type="hidden" name="payment_id" id="payment-id">

			{{-- End --}}

			<div class="form-group">

				<label>Client ID:</label>

				<select class="select2 form-control" name="client_code" onchange="checkClientInfo(this)">

					<option value="0">No Client Selected</option>

					@foreach($clients as $client)

					<option value="{{ $client->id }}">{{ $client->client_code }}</option>

					@endforeach

				</select>
				
			</div>

			<div class="form-group">

				<label>Choose Client:</label>

				<select class="select2 form-control" name="client_id" id="client-name">

					<option value="0">No Client Selected</option>

				</select>
				
			</div>


			<div class="form-group">

				<label>Select Program:</label>

				<select class="select2 form-control" name="program_id" id="programs" onchange="checkClientProgramStep(this)">

				</select>
				
			</div>

			<div class="form-group">

				<label>Select Step:</label>

				<select class="select2 form-control" name="step_id" id="step-number" onchange="checkTotalAmountPaid(this)">

				</select>
				
			</div>

			<div class="form-group">

				<label>Total Amount Paid By Client:</label>

				<input type="number" class="form-control" placeholder="Total Amount" name="total_amount" id="total-amount">
				
			</div>

			<div class="form-group">

				<label>Refund From:</label>
				
				<select class="form-control" name="payment_type" id="refund-from" onchange="paymentOption(this)">

					<option value="0">Select a payment type</option>
					<option value="cash">Cash</option>
					<option value="cheque">Cheque</option>
					<option value="online">Online</option>

				</select>

			</div>

			<div id="payment-container"></div>

			<div class="form-group">

				<label>Refund Amount:</label>

				<input type="number" class="form-control" placeholder="Pay Amount" name="amount" id="amount" required="required">
				
			</div>



			<div class="form-group">

				{{-- <input class="btn btn-danger btn-block button2" type="submit" name="" value="Refund"> --}}

				<button type="button" id="deliveryNext" onclick="myFunction()" class="btn btn-sm btn-success btn-block button2">Refund</button>
				
			</div>

			{{ Form::close() }}

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

	var client_id = 0;
	var program_id = 0;
	var step_id = 0;

	function checkClientInfo(elem) {

		client_id = elem.value;
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
			url: '{!!URL::to('individualClientProgramAndStep')!!}',
			data: {'client_id':client_id},

			success:function(data) {

				for(var i = 0; i < data.length; i++) {

					

					programs += '<option value="'+data[i].program_id+'">'+data[i].program_name+'</option>';

					


				}

				document.getElementById('programs').innerHTML = programs;

			},

		});

		carName = "Ferrari";
		alert(carName);

	}

	function checkClientProgramStep(elem) {

		var option = '';
		program_id = elem.value;

		$.ajax({

			type: 'get',
			url: '{!!URL::to('findClientProgramStep')!!}',
			data: {'program_id':program_id, 'client_id': client_id},

			success:function(data) {

				option += '<option value="0">Please select step</option>';

				for(var i = 0; i < data.length; i++) {

					option += '<option value="'+data[i].id+'">'+data[i].step_name+'</option>';

				}

				document.getElementById('step-number').innerHTML = option;

			},

		});

	}

	function checkTotalAmountPaid(elem) {
		
		step_id = elem.value;
		var total_amount = document.getElementById('total-amount');
		var payment_id = document.getElementById('payment-id');
		var refund_amound = document.getElementById('amount');

		$.ajax({

			type: 'get',
			url: '{!!URL::to('getClientPaymentId')!!}',
			data: {'program_id':program_id, 'client_id': client_id, 'step_id': step_id},

			success:function(data) {

				refund_amound.value = total_amount.value = data.amount_paid;
				payment_id.value = data.payment_id;

			},
			error: function() {
				total_amount.value = null;
				alert('There were no payments made on this program and step!');
			}

		});

	}

	function paymentOption(elem) {

		var payment_type = elem.value;

		if(payment_type == 'cash') {

			var html = '<input type="hidden" name="bank_name" value="cash">';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if (payment_type == 'cheque') {

			var html = '<label>Cheque From:</label> <select class="select2 form-control" name="bank_name""> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br> <label>Cheque Number:</label> <input type="text" name="cheque_number" placeholder="Cheque Number" class="form-control" required> <br></div>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else if(payment_type == 'online') {

			var html = '<label>Select Bank:</label> <select class="select2 form-control" name="bank_name" onchange="addCardCharge(this)"> <option value="scb">SCB</option> <option value="city">City</option> <option value="dbbl">DBBL</option> <option value="ebl">EBL</option> <option value="ucb">UCB</option> <option value="brac">BRAC</option> <option value="agrani">Agrani</option> <option value="icb">ICB</option> </select> <br>';

			$('#payment-container').empty();
			$('#payment-container').append(html);

		} else {
			// 
		}
	}

	function myFunction() {
		var form = document.getElementById('myForm');
		var total_amount = document.getElementById('total-amount').value;
		var refund = document.getElementById('amount').value;


		if(total_amount >= refund) {
	    	form.submit();
	    } else {
	    	alert('Amount refunded cannot be greater than total amount!');
	    }

	    
	}

	$(function(){
	  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
	});


	$(document).ready(function() {
		
		$(".select2").select2({
			placeholder: 'Select a value', 
			allowClear: true
		});
		
	});

	
</script>

@endsection