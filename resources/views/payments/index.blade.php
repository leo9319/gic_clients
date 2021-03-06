@extends('layouts.master')

@section('title', 'Payments')

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

@if (\Session::has('warning'))
    <div class="alert alert-danger">
        <ul>
            <li>{!! \Session::get('warning') !!}</li>
        </ul>
    </div>
@endif

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Receive Payment</h2>

		</div>

		<div class="panel-footer">

			{{ Form::open(['route'=>'payment.types']) }}

			<div class="form-group">

				<label>Date:</label>

				<input type="text" placeholder="Date" name="date" id="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">
				
			</div>

			<div class="form-group">

				<label>Location:</label>

				{!! Form::select('location', $locations, null, ['class' => 'form-control']) !!}

			</div>

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

				<select class="select2 form-control" name="client_id" id="client-name" onchange="checkClientProgram(this)">

					<option value="0">No Client Selected</option>

				</select>

			</div>

			<div class="form-group">

				<label>Select Program:</label>

				<select class="select2 form-control" name="program_id" id="programs" onchange="checkProgramStep(this)">

				</select>

			</div>


			<div class="form-group">

				<label>Select Step:</label>

				<select class="select2 form-control" name="step_id" id="step-number">

				</select>

			</div>

			<div class="form-group">

				<label>Initial Assessment fee:</label>

				<input type="number" id="file-opening-fee" class="form-control" placeholder="Initial Assessment fee" name="opening_fee" onkeyup="sumOfTotal()">

			</div>

			<div class="form-group">

				<label>Lawyer Fees/ Service Charges:</label>

				<input type="number" id="embassy-student-fee" class="form-control" placeholder="Embassy/Student fee" onkeyup="sumOfTotal()" name="embassy_student_fee">

			</div>

			<div class="form-group">

				<label>Government / Third party fees:</label>

				<input type="number" id="service-solicitor-fee" class="form-control" placeholder="Service / Solicitor Charge" name="service_solicitor_fee" onkeyup="sumOfTotal()">

			</div>

			<div class="form-group">

				<label>Other fee:</label>

				<input type="number" id="other-fee" class="form-control" placeholder="Other Fee" name="other" onkeyup="sumOfTotal()">

			</div>

			<div class="form-group">

				<label>Notes:</label>
				
				<textarea class="form-control" name="comments"></textarea>

			</div>

			<div class="form-group">

				<input id="proceed-to-payment" class="btn btn-primary btn-block button2" type="submit" name="" value="Proceed to Payment">

			</div>

			{{ Form::close() }}

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

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


				$.ajax({

					type: 'get',
					url: '{!!URL::to('getClientDues')!!}',
					data: {'client_id':client_id},

					success:function(dues) {

						if(dues > 0) {

							alert('Please clear previous receipt first!');

							$('#proceed-to-payment').attr("disabled", true);

						} else {

							$('#proceed-to-payment').attr("disabled", false);

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

	// function sumOfTotal() {
	// 	var openingFee = document.getElementById("file-opening-fee").value;
	// 	var embassyStudentFee = document.getElementById("embassy-student-fee").value;
	// 	var serviceSolicitorFee = document.getElementById("service-solicitor-fee").value;
	// 	var otherFee = document.getElementById("other-fee").value;

	// 	var totalAmount =  parseInt(openingFee) + parseInt(embassyStudentFee) + parseInt(serviceSolicitorFee) + parseInt(otherFee);

	// 	document.getElementById("total-amount").value = totalAmount;
	// }

	$(function(){
	  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
	});

	$( function() {
		var today = new Date();

	    $("#date").datepicker({
	    	dateFormat: 'yy-mm-dd',
		    maxDate: '0',
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