<!DOCTYPE html>
<html>
<head>
	<title>Expenses PDF</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<h1 class="text-center">Expenses</h1>
	</div>
		<hr>
		<table style="width:100%">
		  <tr>
		    <th>Date</th>
		    <th>Description</th>
		    <th>Deposited To</th>
		    <th>Amount</th>
		  </tr>
		  @foreach($expenses as $expense)
		  <tr>
		    <td>{{ Carbon\Carbon::parse($expense->created_at)->format('d-M-y') }}</td>
		    <td>{{ $expense->description }}</td>
		    <td>{{ strtoupper($expense->bank_name) }}</td>
		    <td>{{ number_format(abs($expense->total_amount)) }}</td>
		  </tr>
		  @endforeach
		</table>
	</div>
</div>
</body>
</html>