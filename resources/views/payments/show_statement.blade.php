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
		      	<b>Customer Code:</b><br>
		      	<b>Customer Name:</b><br>
		      	<b>Spouse Name:</b><br>
		      	<b>Mobile:</b><br>
		      	<b>Email:</b><br>
		      	<br>
		      	<b>Address:</b><br>
		      	<br>
		      	<b>Country Interested in:</b><br>
		      	<b>File Opened On:</b><br>
		      	<br>
		      	<b>RM:</b><br>
		      	<b>Counselor:</b><br>
		      </td>

		      <td colspan="4" class="blank">
		      	{{ $client->client_code }} <br>
		      	{{ $client->name }} <br>
		      	{{ $client_info ? $client_info->spouse_name : 'N/A' }} <br>
		      	{{ $client->mobile }} <br>
		      	{{ $client->email }} <br>
		      	<br>
		      	{{ $client_info ? $client_info->address : 'N/A' }} <br>
		      	<br>

		      	@if($client_info)
		      	@forelse(json_decode($client_info->country_of_choice) as $country)
		      		{{ ucfirst($country) }} 
	      		@empty
	      			N/A
		      	@endforelse
		      	@endif

		      	<br>
		      	{{ $client->created_at }} <br>
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
		      		No RM assigned
		      	@endforelse
		      	<br>
		      </td>

		      <td colspan="2" class="blank" style="border-left: 1px solid black">
		      	Total Amount Paid:<br>
		      	Total Amount Due:<br>
		      	<br>
		      	<br>
		      	<br>
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
		      	{{ number_format($client->totalAmount->sum('total_amount')) }}<br>
		      	{{ number_format($client->totalAmount->sum('total_amount') - $client->totalAmount->sum('amount_paid')) }}<br>
		      	<br>
		      	<br>
		      	<br>
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
		
		<table id="items">
		
		  <tr>
		      <th>Program Name</th>
		      <th>Step Name</th>
		      <th>Date</th>
		      <th>Amount Paid</th>
		      <th>Due</th>
		      <th>Payment Method</th>
		      <th>Total Amount</th>
		  </tr>

		  @foreach($payment_histories as $payment_history)
		  
		  <tr class="item-row">

		      <td>

		      	{{ App\Program::find($payment_history->program_id) ? App\Program::find($payment_history->program_id)->program_name : 'N/A' }}

		      </td>


		      <td>
		      	{{ App\Step::find($payment_history->step_no) ? App\Step::find($payment_history->step_no)->step_name : 'N/A' }}
		      </td>

		      <td>{{ Carbon\Carbon::parse($payment_history->created_at)->format('d-m-y') }}</td>
		      <td>{{ number_format($payment_history->amount_paid) }}</td>
		      <td>{{ number_format($payment_history->total_amount - $payment_history->amount_paid) }}</td>
		      <td>{{ $payment_history->payment_type }}</td>
		      <td>{{ number_format($payment_history->total_amount) }}</td>
		  </tr>

		  @endforeach

		  <tr>
		      <td colspan="6" class="total-line">Grand Total</td>
		      
		      <td class="total-value">
		      	<div id="subtotal">
		      		{{ number_format($client->totalAmount->sum('total_amount')) }}
		      	</div>
		      </td>
		  </tr>
		
		</table>

		<br><br>
		<br><br>
		<br><br>
	
	</div>
	
</body>

</html>