@extends('layouts.master')

@section('url', $previous)

@section('title', 'Dues')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>
				Payment Details:
				<a href="{{ route('payment.client.dues.payment', $payment->id) }}" class="btn btn-success btn-sm button2 pull-right">Clear Due</a>
			</h2>

			<hr>

			<div class="row">
				<div class="col-md-6">
					<p>Client Name: {{ $payment->userInfo->name }}</p>
					<p>Client Code: {{ $payment->userInfo->client_code }}</p>
					<p>Program Name: {{ $payment->programInfo->program_name }}</p>
					<p>Step Name: {{ $payment->stepInfo->step_name }}</p>
				</div>
				<div class="col-md-6 text-right">
					<p>Amount Paid: {{ number_format($program_fee) }}</p>
					<p>Amount Paid: {{ number_format($payment_types->sum('amount_paid')) }}</p>
					<p>Due Amount: {{ number_format($program_fee - $payment_types->sum('amount_paid')) }}</p>
					<p>Payment Made on: {{ Carbon\Carbon::parse($payment->created_at)->format('d M, Y') }}</p>
				</div>
			</div>

			

			<h2>Previous Payments</h2>

			<hr>

			<table class="table">
			  <thead>
			    <tr>
			      <th scope="col">Payment Type</th>
			      <th scope="col">Deposited To</th>
			      <th scope="col">Amount Paid</th>
			    </tr>
			  </thead>
			  <tbody>
			  	@foreach($payment_types as $payment_type)
			    <tr>
			      <td>{{ ucfirst($payment_type->payment_type) }}</td>
			      <td>{{ ucfirst($payment_type->bank_name) }}</td>
			      <td>{{ number_format($payment_type->amount_paid) }}</td>
			    </tr>
			    @endforeach
			    {{-- <tr style="border-top: 2px solid;">
			    	<th>Total Paid</th>
			    	<th></th>
			    	<th>{{ number_format($payment_types->sum('amount_paid')) }}</th>
			    </tr>
			    <tr style="border-bottom: 2px solid">
			    	<th>Due Amount</th>
			    	<th></th>
			    	<th>{{ number_format($payment_types->sum('amount_paid')) }}</th>
			    </tr> --}}
			  </tbody>
			</table>

			<hr>

			



		</div>

	</div>

</div>

@endsection
