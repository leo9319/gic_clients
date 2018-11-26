@extends('layouts.master')

@section('url', $previous)

@section('title', 'Refund Client')

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

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Refund Client</h2>

		</div>

		<div class="panel-footer">

			{{-- {{ Form::open(['autocomplete = off']) }} --}}
			{{ Form::open(['route'=>'payment.store.client.refund']) }}

			<label>Client ID:</label>

			<select class="select2 form-control" name="client_code" onchange="checkClientInfo(this)">

				<option value="0">No Client Selected</option>

				@foreach($clients as $client)

					<option value="{{ $client->id }}">{{ $client->client_code }}</option>

				@endforeach

			</select>

			<br><br>

			<label>Choose Client:</label>

			<select class="select2 form-control" name="client_id" id="client-name" onchange="checkClientProgram(this)">

				<option value="0">No Client Selected</option>

			</select>

			<br><br>

			<div class="form-group">
				{{ Form::label('Refund From:') }}
				{{ Form::select('bank_name', $bank_accounts, null, ['placeholder'=>'Amount to be refunded', 'class'=>'form-control select2']) }}
			</div>

			<div class="form-group">
				{{ Form::label('amount:') }}
				{{ Form::number('amount', null, ['placeholder'=>'Amount to be refunded', 'class'=>'form-control']) }}
			</div>



			<input class="btn btn-danger btn-block button2" type="submit" name="" value="Refund">

			{{ Form::close() }}

		</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">

	function checkClientInfo(elem) {

		var client_id = elem.value;
		var client_name = '';
		var programs = '';

		$.ajax({

			type: 'get',
			url: '{!!URL::to('getClientName')!!}',
			data: {'client_id' : client_id},

			success:function(data) {

				client_name += '<option value="'+data.id+'">'+data.name+'</option>';
				document.getElementById('client-name').innerHTML = client_name;				

			},

		});

	}

	$(document).ready(function() {
		
		$(".select2").select2({
			placeholder: 'Select a value', 
			allowClear: true
		});
		
	});

	
</script>

@endsection