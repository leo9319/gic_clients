@extends('layouts.master')

@section('title', 'Edit Payments')

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

					{{ Form::label('Date:') }}

					{{ Form::date('date', $payment->created_at, ['class'=>'form-control']) }}
					
				</div>

				<div class="form-group">

					{{ Form::label('Location:') }}

					{{ Form::select('location', $locations, null,['class'=>'form-control']) }}
					
				</div>

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

					{{ Form::number('opening_fee', null, ['class'=>'form-control', 'onkeyup'=>'sumOfTotal()', 'id' => 'file-opening-fee']) }}

				</div>

				<div class="form-group">

					<label>Embassy/Student fee:</label>

					{{ Form::number('embassy_student_fee', null, ['class'=>'form-control', 'id' => 'embassy-student-fee', 'onkeyup'=>'sumOfTotal()']) }}

				</div>

				<div class="form-group">

					<label>Service / Solicitor Charge:</label>

					{{ Form::number('service_solicitor_fee', null, ['class'=>'form-control', 'id'=>'service-solicitor-fee', 'onkeyup'=>'sumOfTotal()']) }}

				</div>

				<div class="form-group">

					<label>Other fee:</label>

					{{ Form::number('other', null, ['class'=>'form-control', 'id' => 'other-fee', 'onkeyup'=>'sumOfTotal()']) }}

				</div>

				<div class="form-group">

					<label>Due Clearance Date:</label>
					<input type="text" placeholder="Due Date" name="due_date" id="due-date" class="form-control" value="{{ $payment->due_date }}">

				</div>

				<div class="form-group">

					<label>Note:</label>

					{{ Form::textarea('comments', null, ['class'=>'form-control', 'rows'=>3]) }}

				</div>

				<br>

				<h2>Payment Structure</h2>
				<hr>

				@forelse($payment_types as $index => $payment_type)
					@if($payment_type->payment_type == 'cash' || $payment_type->payment_type == 'cash_dhaka'|| $payment_type->payment_type == 'cash_ctg')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group">
							<label>Amount Paid:</label>

							<input type="text" class="form-control" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}">					
						</div>

						<input type="hidden" name="payment[{{ $index }}][bank_name]" value={{ $payment_type->bank_name }}>

						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>

						<hr>

					@elseif($payment_type->payment_type == 'card')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{$payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group">
						   <label>Card Type:</label> 
						   <input type="text" class="form-control" name="payment[{{$index}}][card_type]" value="{{ $payment_type->card_type }}" readonly="">
						</div>

						<div class="form-group">
						   <label>Name on card:</label> 
						   <input type="text" name="payment[{{$index}}][name_on_card]" placeholder="Name on card" class="form-control" value="{{ $payment_type->name_on_card }}"> 
						</div>

						<div class="form-group">
						   <label>Card Number (Last 4 Digits Only):</label>
						   <input type="text" name="payment[{{$index}}][card_number]" maxlength="4" placeholder="Card Number" class="form-control" value="{{ $payment_type->card_number }}" required> 
						</div>

						<div class="form-group">
						   <label>Expiry Date:</label> 
						   <input type="text" name="payment[{{$index}}][expiry_date]" placeholder="Expiry Date" value="{{ $payment_type->expiry_date }}" class="form-control"> 
						</div>

						<div class="form-group">
						   <label>Select Card/POS Machine:</label> 
						   <input type="text" name="payment[{{$index}}][pos_machine]" class="form-control" value="{{ $payment_type->pos_machine }}" readonly="">
						</div>

						<div class="form-group">
						   <label>Approval Code:</label> 
						   <input type="text" name="payment[{{$index}}][approval_code]" placeholder="Approval Code" class="form-control" value="{{ $payment_type->approval_code }}"> 
						</div>

						<div class="form-group">
						   <label>Total Amount:</label> 
						   <input type="number" class="total form-control" value="{{ $payment_type->amount_paid }}" placeholder="Amount paid through POS" name="payment[{{$index}}][amount_paid]" onchange="getTotalAmount(this)" required>
						</div>

						<input type="hidden" name="payment[{{ $index }}][pos_machine]" value={{ $payment_type->pos_machine }}>

						<input type="hidden" name="payment[{{ $index }}][bank_name]" value={{ $payment_type->bank_name }}>

						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>

						<hr>
						

					@elseif($payment_type->payment_type == 'cheque')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group">

							<label>Cheque Deposited To:</label> 
							<input type="text" name="payment[{{$index}}][bank_name]" class="form-control" value="{{ $payment_type->bank_name }}" readonly="">

						</div>

						<div class="form-group">

							<label>Cheque Number:</label> 
							<input type="text" name="payment[{{$index}}][cheque_number]" placeholder="Cheque Number" class="form-control" value="{{ $payment_type->cheque_number }}"> 

						</div>

						<div class="form-group">

							<label>Total Amount:</label> 
							<input type="number" class="form-control" placeholder="Amount paid in cheque" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}">

						</div>

						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>

						<input type="hidden" name="payment[{{ $index }}][cheque_verified]" value={{ $payment_type->cheque_verified }}>

						<hr>
					

					@elseif($payment_type->payment_type == 'bkash_corporate')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group"> 
							<label>GIC Deposit Bank Name:</label> 
							<input type="text" name="payment[{{$index}}][bank_name]" placeholder="GIC Deposit Bank Name" class="form-control" value="scb" readonly> 
						</div>


						<div class="form-group"> 
							<label>Phone Number</label> 
							<input type="text" name="payment[{{$index}}][phone_number]" placeholder="Phone Number" class="form-control" value="{{ $payment_type->phone_number }}" required> 
						</div>
						 

						<div class="form-group"> 
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}">
						</div>

						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>
						<input type="hidden" name="payment[{{ $index }}][bkash_corporate_verified]" value={{ $payment_type->bkash_corporate_verified }}>

						<hr>

					@elseif($payment_type->payment_type == 'bkash_salman')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group">
							<label>GIC Deposit Bank Name:</label> 
							<input type="text" name="payment[{{$index}}][bank_name]" placeholder="GIC Deposit Bank Name" class="form-control" value="salman account" readonly>  
						</div>

						<div class="form-group">
							<label>Phone Number</label> 
							<input type="text" name="payment[{{$index}}][phone_number]" placeholder="Phone Number" class="form-control" value="{{ $payment_type->phone_number }}"> 
						</div>
						 

						<div class="form-group">
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}" required>
						</div>

						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>
						<input type="hidden" name="payment[{{ $index }}][bkash_salman_verified]" value={{ $payment_type->bkash_salman_verified }}>

						<hr>

					@elseif($payment_type->payment_type == 'upay')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group">
							<label>GIC Deposit Bank Name:</label> 
							<input type="text" name="payment[{{$index}}][bank_name]" placeholder="GIC Deposit Bank Name" class="form-control" value="ucb" readonly> 
						</div>
						 

						<div class="form-group">
							<label>Phone Number</label> 
							<input type="text" name="payment[{{$index}}][phone_number]" placeholder="Phone Number" class="form-control" value="{{ $payment_type->phone_number }}" required="">  
						</div>


						<div class="form-group">
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}" required>
						</div>

						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>

						<hr>

					@elseif($payment_type->payment_type == 'online')

						<div class="form-group">
							<label>Payment Type:</label>
							
							<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
						</div>

						<div class="form-group">
							<label>Date:</label>
							
							<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
						</div>

						<div class="form-group">
							<label>Select Bank:</label> 
							<input type="text" name="payment[{{$index}}][bank_name]" class="form-control" value="{{ $payment_type->bank_name }}" readonly="">
						</div>


						<div class="form-group">
							<label>Deposit Date:</label> 
							<input type="date" class="form-control" name="payment[{{$index}}][deposit_date]" value="{{ $payment_type->deposit_date }}" required>
						</div>
						 

						<div class="form-group">
							<label>Total Amount:</label> 
							<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}" required>
						</div>



						<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>
						<input type="hidden" name="payment[{{ $index }}][online_verified]" value={{ $payment_type->online_verified }}>
 

					</div>

					@elseif($payment_type->payment_type == 'pay_gic')

					<div class="form-group">
						<label>Payment Type:</label>
							
						<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
					</div>

					<div class="form-group">
						<label>Date:</label>
						
						<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
					</div>

					<div class="form-group">
						<label>Select Bank:</label> 
						<input type="text" name="payment[{{$index}}][bank_name]" class="form-control" value="{{ $payment_type->bank_name }}" readonly="">
					</div>

					<div class="form-group">
						<label>Total Amount:</label> 
						<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}" required>
					</div>

					<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>
					<input type="hidden" name="payment[{{ $index }}][online_verified]" value={{ $payment_type->online_verified }}>

					{{-- Pay GIC SSL --}}

					@elseif($payment_type->payment_type == 'pay_gic_ssl')

					<div class="form-group">
						<label>Payment Type:</label>
							
						<input type="text" class="form-control" name="payment[{{$index}}][payment_type]" value="{{ $payment_type->payment_type }}" readonly="">
					</div>

					<div class="form-group">
						<label>Date:</label>
						
						<input type="date" class="form-control" name="payment[{{$index}}][created_at]" value="{{ Carbon\Carbon::parse($payment_type->created_at)->format('Y-m-d') }}">
					</div>

					<div class="form-group">
						<label>Select Bank:</label> 
						<input type="text" name="payment[{{$index}}][bank_name]" class="form-control" value="{{ $payment_type->bank_name }}" readonly="">
					</div>

					<div class="form-group">
						<label>Total Amount:</label> 
						<input type="number" class="total form-control" placeholder="Amount paid in bKash" name="payment[{{$index}}][amount_paid]" value="{{ $payment_type->amount_paid }}" required>
					</div>

					<input type="hidden" name="payment[{{ $index }}][bank_charge]" value={{ $payment_type->bank_charge }}>
					<input type="hidden" name="payment[{{ $index }}][online_verified]" value={{ $payment_type->online_verified }}>

					@endif

				@empty


				@endforelse

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

	$(function(){
	  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
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

