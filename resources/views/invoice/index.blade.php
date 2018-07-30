<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />	
	<title>Customer Invoice</title>
	<link rel='stylesheet' type='text/css' href="{{asset('css/style.css')}}" />
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">

</head>
<body>
	<div id="page-wrap">
		<div id="header">INVOICE</div>

		<div id="identity">
        <div id="address">
        	<div id="company-name">Global Immigration Consultants Ltd (GIC)</div> 
        	<div>Gulshan 1</div> 
        	<div>1st Floor, Plot 56/b, Road No 132</div> 
        	<div>Dhaka-1212, Bangladesh</div>
		</div>
			<div id="logo">
				<img src="{{asset('img/logo2.png')}}" alt="logo" height="115" width="190"/>
			</div>
		</div>
		
		<div style="clear:both"></div>

		<div class="row">
			<div class="col-md-6">
				<table class="borderless">
				  <tr>
				    <td class="text-right font-weight-bold">Full Name:</td>
				    <td>{{ $client->name }}</td> 
				  </tr>
				  <tr>
				    <td class="text-right font-weight-bold">Present Address:</td>
				    <td>{{ $client_file_info->address }}</td>
				  </tr>
				  <tr>
				    <td class="text-right font-weight-bold">Phone Number:</td>
				    <td>{{ $client->mobile }}</td>
				  </tr>
				  <tr>
				    <td class="text-right font-weight-bold">Email Address:</td>
				    <td>{{ $client->email }}</td>
				  </tr>
				</table>
			</div>
			<div class="col-md-6">
				<table id="meta">
					<tr>
	                    <td class="meta-head">Date Opened</td>
	                    <td>{{ Carbon\Carbon::parse($client->created_at)->format('d/m/Y') }}</td>
	                </tr>
	                <tr>
	                    <td class="meta-head">Client Code</td>
	                    <td>{{ $client->client_code }}</td>
	                </tr>  
	                <tr>
	                    <td class="meta-head">Invoice created by</td>
	                    <td>{{ Auth::user($client_file_info->creator_id)->name }}</td>
	                </tr> 
            	</table>
			</div>	
		</div>
		
		<table id="items">
		  <tr>
		  	  <th>Programs</th>
		      <th>Country of choice</th>
		      <th>File opening fee</th>
		      <th>Embassy/Student fee</th>
		      <th>Service / Solicitor Charge</th>
		      <th>Other fee</th>
		      <th>Total</th>
		  </tr>
		  <tr id="hiderow">
		    <td>
		    	@forelse($client_programs as $client_program)
		    		@foreach($client_program->programInfo as $program)
		    			<li>{{ $program->program_name }}</li>
		    		@endforeach
			    @empty
			    	<li>No programs selected</li>
		    	@endforelse
		    </td>
		    <td>
		    	@foreach(json_decode($client_file_info->country_of_choice, true) as $key => $value)
				    <li>{{ ucfirst($value) }}</li>
				@endforeach
		    </td>
		    <td>0</td>
		    <td>Paid in Cash</td>
		    <td>0</td>
		    <td>0</td>
		    <td>{{ number_format($client_file_info->amount_paid) }}</td>
		  </tr>
		</table>
		
		<div id="clearance">
			<div class="row">
				<span class="col-md-4 font-weight-bold"><u>Payment Received By</u></span>
				<span class="col-md-4 font-weight-bold"><u>Client Signature</u></span>
				<span class="col-md-4 font-weight-bold text-right"><u>Received By</u></span>
			</div>
		</div>
		<div id="terms">
		  <div>If you have any questions concerning this invoice, contact us @ 09678744223.</div>
		</div>
	</div>

</body>
</html>
