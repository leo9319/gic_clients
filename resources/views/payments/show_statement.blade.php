<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
	
	<title>Statement</title>
	
	<link rel='stylesheet' type='text/css' href='{{ asset('css/statement/style.css') }}' />
	<link rel='stylesheet' type='text/css' href='{{ asset('css/statement/print.css') }}' media="print" />

</head>

<body>

	<div id="page-wrap">

		<textarea id="header">Statement of Account</textarea>

		<table id="items">
		  
		  <tr>
		      <td colspan="1" class="blank"> 
		      	<b>Client Code:</b><br>
		      	<b>Client Name:</b><br>
		      	<b>Spouse Name:</b><br>
		      	<b>Mobile:</b><br>
		      	<b>Email:</b><br>
		      	<br>
		      	<b>Address:</b>
		      	<br>
		      	<br>
		      	<b>File Opened On:</b><br>
		      	<br>
		      	<b>RM:</b><br>
		      	<b>Counselor:</b><br>
		      	<br>
		      	<br>
		      </td>

		      <td colspan="4" class="blank">
		      	{{ $client->client_code }} <br>
		      	{{ $client->name }} <br>
		      	{{ $client->getAdditionalInfo->spouse_name ?? 'N/A' }} <br>
		      	{{ $client->mobile }} <br>
		      	{{ $client->email }} <br>
		      	<br>
		      	{{ $client->getAdditionalInfo->address ?? 'N/A'}} <br>
		      	<br>
		      	{{ Carbon\Carbon::parse($client->created_at)->format('jS F, Y') }} <br>
		      	<br>

		      	@forelse($rms as $rm)
		      		{{ App\User::find($rm->rm_id) ? App\User::find($rm->rm_id)->name : '' }},
		      	@empty
		      		No RM assigned
		      	@endforelse

		      	<br>
		      	@forelse($counselors as $counselor)
		      		{{ App\User::find($counselor->counsellor_id) ? App\User::find($counselor->counsellor_id)->name : '' }},
		      	@empty
		      		No Counselor assigned
		      	@endforelse
		      	<br>
		      	<br>
		      	<br>
		      </td>

		      <td colspan="2" class="blank" style="border-left: 1px solid black">
		      	Total Program Costs:<br>
		      	Total Amount Received:<br>
		      	Dues:
		      	<br>
		      	<br>
		      	Refunded:<br>
		      	Total Received after dues and refund:<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      </td>
		      <td colspan="2" class="blank">
		      	{{ number_format($payable) }}<br>
		      	{{ number_format($payment_methods
		      		->where('cheque_verified', '!=', 0)
		      		->where('online_verified', '!=', 0)
		      		->where('bkash_salman_verified', '!=', 0)
		      		->where('refund_payment', 0)
		      		->sum('amount_paid')) }}<br>
		      	{{ number_format($dues) }}<br>
		      	<br>
		      	{{ number_format($refunds->sum('amount_paid')) }}<br>
		      	{{ number_format($payment_methods
		      		->where('cheque_verified', '!=', 0)
		      		->where('online_verified', '!=', 0)
		      		->where('bkash_salman_verified', '!=', 0)
		      		->where('refund_payment', 0)
		      		->sum('amount_paid') - $refunds->sum('amount_paid')) }}<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      	<br>
		      </td>
		  </tr>
		
		</table>

		<br>

		<h3>Programs:</h3>
		
		<table id="items">
		
		  <tr>
		      <th>Program Name</th>
		      <th>Step Name</th>
		      <th>Date</th>
		      <th>Total Amount</th>
		      <th>Amount Paid</th>
		      <th>Due</th>
		  </tr>

		  @foreach($payment_histories as $payment_history)
		  
		  <tr class="item-row">

		      <td>{{ $payment_history->programInfo->program_name }}</td>
		      <td>{{ $payment_history->stepInfo->step_name }}</td>
		      <td>{{ Carbon\Carbon::parse($payment_history->created_at)->format('d-m-y') }}</td>
		      <td>{{ number_format($payment_history->totalAmount()) }}</td>
		      <td>
		      	{{ number_format($payment_history->totalPayment
		      		->where('cheque_verified', '!=', 0)
                    ->where('online_verified', '!=', 0)
                    ->where('bkash_salman_verified', '!=', 0)
		      		->where('refund_payment', 0)
		      		->sum('amount_paid')) }}
		      </td>
		      <td>{{ number_format($payment_history->totalAmount() - $payment_history->totalVerifiedPayment->sum('amount_paid')) }}</td>

		  </tr>

		  @endforeach
		
		</table>

		<br>

		<h3>Refunds:</h3>

		<table id="items">
		
		  <tr>
		      <th>Program Name</th>
		      <th>Step Name</th>
		      <th>Bank Name</th>
		      <th>Amount Refunded</th>
		  </tr>

		  @foreach($refunds as $refund)

		  <tr class="item-row">
		  	<td>{{ $refund->payment->programInfo->program_name }}</td>
		  	<td>{{ $refund->payment->stepInfo->step_name }}</td>
		  	<td>{{ strtoupper($refund->bank_name) }}</td>
		  	<td>{{ number_format($refund->amount_paid) }}</td>
		  </tr>

		  @endforeach

		</table>

		<br><br>
		<br><br>
		<br><br>
	
	</div>
	
</body>

</html>