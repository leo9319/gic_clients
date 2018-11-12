@extends('layouts.master')

@section('url', $previous)

@section('title', 'Create Expense')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

@endsection

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
@endif

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Expense</h2>

		</div>

		<div class="panel-footer">

			{{ Form::open(['route' => 'payment.store.income.and.expenses']) }}

			{!! Form::hidden('type', 'expense') !!}

			<div class="form-group">

				{!! Form::label('Date:') !!}
			
				{!! Form::date('date', date('Y-m-d'), ['class' => 'form-control']) !!}

			</div>

			<div class="form-group">

				{!! Form::label('Amount:') !!}
			
				{!! Form::number('amount', null, ['Placeholder'=>'Amount', 'class' => 'form-control', 'required']) !!}

			</div>

			<div class="form-group">

				{!! Form::label('Description:') !!}
			
				{!! Form::textarea('description', null, ['class' => 'form-control']) !!}

			</div>

			<div class="form-group">

				{!! Form::label('Account Name:') !!}
			
				{!! Form::select('bank_name', $bank_accounts, null, ['class' => 'form-control']) !!}

			</div>

			<br>

			<div class="form-group">

				{!! Form::submit('Submit', ['class'=>'btn btn-primary btn-block button2']) !!}

			</div>

		</div>

	</div>

</div>

@endsection