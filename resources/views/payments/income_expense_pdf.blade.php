<!DOCTYPE html>
<html>
<head>
	<title>Income and Expense PDF</title>
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
		<h1 class="text-center">Incomes and Expenses</h1>
	</div>

	<div class="row">
		<div class="col-md-6">
			<h3 class="text-center">Incomes</h3>
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
		<div class="col-md-6">
			<h3 class="text-center">Expenses</h3>
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
	<hr>
	<h5>Overall Balance: {{ number_format($incomes->sum('total_amount') + $expenses->sum('total_amount')) }} tk</h5>
	<hr>

</div>
</body>
</html>