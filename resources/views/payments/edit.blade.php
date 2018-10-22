@extends('layouts.master')

@section('url', $previous)

@section('title', 'Edit Payment')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

@endsection

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Edit Payment</h2>

		</div>

		<div class="panel-footer">

			{{ Form::model($payment, ['route' => ['payment.update', $payment->id]]) }}

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

					{{ Form::select('program_id', $programs->pluck('program_name', 'id'), null, ['class'=>'form-control', 'onchange' => 'getSteps(this)']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Client Step:') }}

					{{ Form::select('step_no', [$payment->step_no=>App\Step::find($payment->step_no)->step_name], null, ['class'=>'form-control', 'id' => 'step-number']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Payment Type:') }}

					<select class="select2 form-control" name="payment_type" onchange="addPaymentOptions(this)">

						<option value="cash">Cash</option>
						<option value="card">POS</option>
						<option value="cheque">Cheque</option>
						<option value="bkash_corporate">bKash - Corporate</option>
						<option value="bkash_salman">bKash - Salman</option>
						<option value="upay">Upay</option>
						<option value="online">Online</option>

					</select>
					
				</div>

				<div class="form-group">

					<label>File opening fee:</label>

					{{ Form::number('opening_fee', null, ['class'=>'form-control', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Embassy/Student fee:</label>

					{{ Form::number('embassy_student_fee', null, ['class'=>'form-control', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Service / Solicitor Charge:</label>

					{{ Form::number('service_solicitor_fee', null, ['class'=>'form-control', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Other fee:</label>

					{{ Form::number('other', null, ['class'=>'form-control', 'onkeyup'=>'sumOfTotal()', 'required']) }}

				</div>

				<div class="form-group">

					<label>Total Amount:</label>

					{{ Form::number('total_amount', null, ['class'=>'form-control', 'required']) }}

				</div>

				<div class="form-group">

					<label>Due Clearance Date:</label>

					{{ Form::date('due_clearance_date', null, ['class'=>'form-control', 'required']) }}

				</div>

				<br>

				<div class="form-group">
					{{ Form::submit('update', ['class'=>'btn btn-primary btn-block button2']) }}
				</div>

			{{ Form::close() }}
			
		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">
	
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

	$(document).ready(function() {
		
		$(".select2").select2({
			placeholder: 'Select a value', 
			allowClear: true
		});
		
	});

</script>

@endsection

