<!DOCTYPE html>
<html>
<head>
	<title>Monthly Report</title>
	<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
  <script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.js"></script>
</head>
<body>
<div class="container">
	<div class="jumbotron">
		<h1 class="text-center">Profit and Loss</h1>
	</div>

	<div class="row">
		<div class="col-md-12">
			<h3 class="text-center">All Incomes and Expenses</h3>
			<hr>
			<table style="width:100%">
			  <tr>
			    <th>Date</th>
			    <th>Description</th>
			    <th>Type</th>
			    <th>Bank</th>
			    <th>Amount</th>
			  </tr>
			  @foreach($reports as $key => $value)
			  <tr>
			    <td>{{ $value['date'] }}</td>
			    <td>{{ $value['description'] }}</td>
			    <td>{{ $value['type'] }}</td>
			    <td>{{ $value['bank'] }}</td>
			    <td>{{ number_format($value['amount']) }}</td>
			  </tr>
			  @endforeach
			  <tr>
			  	<td></td>
			  	<td></td>
			  	<td></td>
			  	<td></td>
			  	<td><b>{{ number_format($sum) }}</b></td>
			  </tr>
			</table>
			<hr>
		</div>
	</div>

</div>
</body>
</html>