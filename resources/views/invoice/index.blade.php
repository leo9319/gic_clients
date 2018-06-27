<!DOCTYPE html>
<html>
<head>
	<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />	
	<title>Customer Invoice</title>
	<link rel='stylesheet' type='text/css' href="{{asset('css/style.css')}}" />
	<link rel='stylesheet' type='text/css' href="{{asset('css/print.css')}}" media="print" />
	<script type='text/javascript' src="{{asset('js/jquery-1.3.2.min.js')}}"></script>
	<script type='text/javascript' src="{{asset('js/example.js')}}"></script>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
	<!-- <link href="https://fonts.googleapis.com/css?family=Titillium+Web" rel="stylesheet"> -->

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
				    <td> Farhana Shahid
				    	
                	</td> 
				  </tr>
				  <tr>
				    <td class="text-right font-weight-bold">Present Address:</td>
				    <td> 66, Monipuripara, Dhaka - 1215
                    	
                	</td>
				  </tr>
				  <tr>
				    <td class="text-right font-weight-bold">Phone Number:</td>
				    <td> 01937675131
                    	
                	</td>
				  </tr>
				  <tr>
				    <td class="text-right font-weight-bold">Email Address:</td>
				    <td> farhana_89@yahoo.com
                		
                	</td>
				  </tr>
				</table>
			</div>
			<div class="col-md-6">
				<table id="meta">
					<tr>
	                    <td class="meta-head">Date</td>
	                    <td>{{ Carbon\Carbon::today()->format('d/m/Y') }}</td>
	                </tr>
	                <tr>
	                    <td class="meta-head">Receipt No.</td>
	                    <td>6435</td>
	                </tr>  
	                <tr>
	                    <td class="meta-head">Invoice created by</td>
	                    <td>Sourav</td>
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
		    	<li>Australia</li>
		    	<li>Canada</li>
		    	<li>Malaysia</li>
		    </td>
		    <td>Canada</td>
		    <td>0</td>
		    <td>Paid in Cash</td>
		    <td>0</td>
		    <td>0</td>
		    <td>24,600</td>
		  </tr>
		</table>

		<p style="padding-top: 20px;">Amount in words: <span class="font-weight-bold">Twenty four thousand six hundred only</span></p>
		
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
