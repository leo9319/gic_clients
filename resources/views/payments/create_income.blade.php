@extends('layouts.master')

@section('url', $previous)

@section('title', 'Create Income')

@section('content')

@section('header_scripts')

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

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

			<h2>Income</h2>

		</div>

		<div class="panel-footer">

			{{ Form::open(['route' => 'payment.store.income.and.expenses']) }}

			{!! Form::hidden('type', 'income') !!}

			<div class="form-group">

				{!! Form::label('Date:') !!}
			
				{{-- {!! Form::date('date', date('Y-m-d'), ['class' => 'form-control']) !!} --}}
				<input type="text" placeholder="Date" name="date" id="date" class="form-control" value="{{ Carbon\Carbon::today()->format('Y-m-d') }}">

			</div>

			<div class="form-group">

				{!! Form::label('Amount:') !!}
			
				{!! Form::number('amount', null, ['Placeholder'=>'Amount', 'class' => 'form-control', 'required']) !!}

			</div>

			<div class="form-group">

				{!! Form::label('Description:') !!}
			
				{!! Form::textarea('description', null, ['class' => 'form-control', 'rows'=>3]) !!}

			</div>

			<div class="form-group">

				{!! Form::label('Location:') !!}
			
				{!! Form::select('location', $locations, null, ['class' => 'form-control']) !!}

			</div>

			<div class="form-group">

				{!! Form::label('Account Name:') !!}
			
				{!! Form::select('bank_name', $bank_accounts, null, ['class' => 'form-control']) !!}

			</div>

			<div class="form-group">

				{!! Form::label('advance_payment:') !!}

				<br>
			
				{!! Form::radio('advance_payment', 'yes', true) !!} Yes

				<br>

				{!! Form::radio('advance_payment', 'no', true) !!} No

			</div>

			<br>

			<div class="form-group">

				{!! Form::submit('Submit', ['class'=>'btn btn-primary btn-block button2']) !!}

			</div>

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

	$(function(){
	  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
	});
	
	$( function() {
		var today = new Date();

	    $("#date").datepicker({
	    	dateFormat: 'yy-mm-dd',
		    maxDate: '0',
		});
	});
	
</script>

@endsection