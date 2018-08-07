@extends('layouts.master')

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