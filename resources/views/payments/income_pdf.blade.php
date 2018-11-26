<!DOCTYPE html>
<html>
<head>
	<title>Incomes PDF</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<h1 class="text-center">Incomes</h1>
	</div>
		<hr>
		<table style="width:100%">
		  <tr>
		    <th>Date</th>
		    <th>Description</th>
		    <th>Deposited To</th>
		    <th>Amount</th>
		  </tr>
		  @foreach($incomes as $income)
		  <tr>
		    <td>{{ Carbon\Carbon::parse($income->created_at)->format('d-M-y') }}</td>
		    <td>{{ $income->description }}</td>
		    <td>{{ strtoupper($income->bank_name) }}</td>
		    <td>{{ number_format($income->total_amount) }}</td>
		  </tr>
		  @endforeach
		</table>
	</div>
</div>
</body>
</html>