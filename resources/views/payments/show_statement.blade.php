@extends('layouts.master')

@section('title', 'Statement of Accounts')

@section('content')

<div class="container-fluid">

   <div class="panel">

      <div class="panel-body">

         <h2 class="text-center">Statement of Accounts</h2>

         <hr>

         <div class="row">
            <div class="col-md-6">
            	<p><b>Client Code:</b> {{ $client->client_code ?? 'N/A' }}</p>
                <p><b>Client Name:</b> {{ $client->name ?? 'N/A' }}</p>
                <p><b>Spouse:</b> {{ $client->getAdditionalInfo->spouse_name ?? 'N/A' }}</p>
                <p><b>Mobile:</b> {{ $client->mobile ?? 'N/A' }}</p>
                <p><b>Email:</b> {{ $client->email ?? 'N/A' }}</p>
                <p><b>Address:</b> {{ $client->getAdditionalInfo->address ?? 'N/A'}}</p>
                <p><b>Client Registered On:</b> {{ Carbon\Carbon::parse($client->created_at)->format('jS F, Y') }}</p>
                <p><b>RM:</b> 
                	@forelse($rms as $rm)
                  		{{ $rm->user->name ?? 'N/A' }} 
               		@empty
                  		No RM assigned
           			@endforelse
           		</p>
                <p><b>Counselor:</b>
					@forelse($counselors as $counselor)
                  		{{ $counselor->user->name ?? 'N/A' }} 
               		@empty
                  		No Counselor assigned
           			@endforelse
                </p>
            </div>
            <div class="col-md-6 text-right">
               <p><b>Total Program Costs:</b> {{ number_format($payable) }}</p>
               <p><b>Total Amount Received: </b> {{ number_format($payment_received) }}</p>
               <p><b>Dues:</b> {{ number_format($payable - $payment_received) }}</p>
               <p><b>Refunded:</b> {{ number_format($refunds->sum('amount_paid')) }}</p>
               <p><b>Total Received after dues and refund:</b> {{ number_format($payment_received - $refunds->sum('amount_paid')) }}</p>
            </div>
         </div>

         <hr>

        <h3><b>Payment History</b></h3>

        <hr>

        <table class="table">
           <thead>
             <tr>
               <th scope="col">Date</th>
               <th scope="col">Program Name</th>
               <th scope="col">Step Name</th>
               <th scope="col">Total Amount</th>
               <th scope="col">Amount Paid</th>
               <th scope="col">Due</th>
             </tr>
           </thead>
           <tbody>
           	@foreach($payment_histories as $payment_history)
             <tr>
               <td>{{ Carbon\Carbon::parse($payment_history->created_at)->format('d-M-y') }}</td>
               <td>{{ $payment_history->programInfo->program_name ?? 'N/A'}}</td>
               <td>{{ $payment_history->stepInfo->step_name ?? 'N/A'}}</td>
               <td>{{ number_format($payment_history->totalAmount()) }}</td>
               <td>{{ number_format($payment_history->totalApprovedPayment->sum('amount_paid')) }}</td>
               <td>{{ number_format($payment_history->totalAmount() - $payment_history->totalApprovedPayment->sum('amount_paid')) }}
               </td>
             </tr>
             @endforeach
           </tbody>
        </table>

        <hr>

        @if(count($refunds) > 0)

        <h3><b>Refunds:</b></h3>

	    <table class="table">
	    
	      <tr>
	          <th>Date</th>
            <th>Program Name</th>
	          <th>Step Name</th>
	          <th>Bank Name</th>
	          <th>Amount Refunded</th>
	      </tr>

	      @foreach($refunds as $refund)

	      <tr class="item-row">
	       <td>{{ Carbon\Carbon::parse($refund->created_at)->format('d-M-y') }}</td>
         <td>{{ $refund->payment->programInfo->program_name }}</td>
	       <td>{{ $refund->payment->stepInfo->step_name }}</td>
	       <td>{{ strtoupper($refund->bank_name) }}</td>
	       <td>{{ number_format($refund->amount_paid) }}</td>
	      </tr>

	      @endforeach

	    </table>

	    @endif

      </div>

   </div>

</div>

@endsection
