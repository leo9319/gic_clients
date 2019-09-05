@extends('layouts.master')

@section('title', 'Edit Income and Expenses')

@section('content')

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Edit Income / Expenses</h2>

			@if(session()->has('message'))
			    <div class="alert alert-success">
			        {{ session()->get('message') }}
			    </div>
			@endif

			@if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

		</div>

		<div class="panel-footer">

			{!! Form::open(['route' => 'payment.update.income.and.expenses']) !!}

        		{!! Form::hidden('id', $income_expense->id) !!}

				<div class="form-group">

					{!! Form::label('Date:') !!}

					{!! Form::date('date', $income_expense->created_at, ['class' => 'form-control']) !!}

				</div>

				<div class="form-group">

					{!! Form::label('Payment Type:') !!}

					{!! Form::select('payment_type', ['income'=>'Income', 'expense'=>'Expense'], $income_expense->payment_type, ['class' => 'form-control']) !!}

				</div>

				<div class="form-group">

					{!! Form::label('Amount:') !!}

					{!! Form::number('amount', $income_expense->total_amount, ['Placeholder'=>'Amount', 'class' => 'form-control', 'required']) !!}

				</div>

				<div class="form-group">

		        	{!! Form::label('Description:') !!}
		        
		        	{!! Form::textarea('description', $income_expense->description, ['class' => 'form-control', 'rows'=>'4']) !!}

		        </div>

		        <div class="form-group">

		        	{!! Form::label('Account Name:') !!}
		        
		        	{!! Form::select('bank_name', $bank_accounts, $income_expense->bank_name, ['class' => 'form-control']) !!}

		        </div>

		        <div class="form-group">

		        	{!! Form::label('Location:') !!}
		        
		        	{!! Form::select('location', ['dhaka'=>'Dhaka', 'chittagong'=>'Chittagong'], $income_expense->location, ['class' => 'form-control']) !!}

		        </div>

				<div class="form-group">

					{!! Form::label('advance_payment:') !!}

					<br>

					<input type="radio" name="advance_payment" value="yes" id="advance-payment-yes" {{ ($income_expense->advance_payment=="yes") ? "checked" : "" }}> Yes

					<br>

					<input type="radio" name="advance_payment" value="no" id="advance-payment-no" {{ ($income_expense->advance_payment=="no") ? "checked" : "" }}> No

				</div>

				<button type="submit" class="btn btn-info btn-block">Update</button>
        		<a href="javascript:window.open('','_self').close();" class="btn btn-danger btn-block">Cancel</a>

            {!! Form::close() !!}

      	</div>

	</div>

</div>

@endsection