@extends('layouts.master')

@section('title', 'Create Reminders')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

@endsection

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Create Reminders</h2>

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

			{!! Form::open(['route' => 'reminders.store']) !!}

				<div class="form-group">

					{{ Form::label('Client Code:') }}

					{{ Form::select('client_id', $clients->pluck('client_code', 'id'), null, ['id' => 'client_id', 'class'=>'form-control select2']) }}

				</div>

				<div class="form-group">

					{{ Form::label('Client Name:') }}

					{{ Form::text('client_name', null, ['id' => 'client-name', 'class'=>'form-control select2']) }}

				</div>

				<div class="form-group">

					{{ Form::label('Client Phone(s):') }}

					<div class="row">
						
						<div class="col-md-10">

							{{ Form::number('mobile[]', null, ['id' => 'mobile', 'placeholder' => 'Mobile' ,'class'=>'form-control', 'required']) }}
							
						</div>

						<div class="col-md-2">

							<button type="button" class="btn btn-success" onclick="addNumber()">
								+ Add More
							</button>
							
						</div>

					</div>
					
				</div>

				<div id="number-container"></div>

				<div class="form-group">

					{{ Form::label('Client Email(s):') }}


					<div class="row">
						
						<div class="col-md-10">

							{{ Form::email('email[]', null, ['id' => 'email', 'placeholder' => 'Email', 'class'=>'form-control', 'required']) }}
							
						</div>

						<div class="col-md-2">

							<button type="button" class="btn btn-success" onclick="addEmail()">
								+ Add More
							</button>
							
						</div>

					</div>

				</div>

				<div id="email-container"></div>

				<div class="form-group">

					{{ Form::label('message:') }}

					<div class="row">
						
						<div class="col-md-10">

							{{ Form::textarea('message', null, ['id' => 'message', 'class' => 'form-control', 'rows' => '4', 'required']) }}
							
						</div>

						<div class="col-md-2">

							<button type="button" id="template-1" class="btn btn-info" style="margin-bottom: 10px" onclick="addTemplate(this)">
								Lawyer's 2nd Step
							</button>

							<button type="button" id="template-2" class="btn btn-info" onclick="addTemplate(this)">
								ECA Template
							</button>
							
						</div>

					</div>
					
				</div>

				<div class="form-group">

					{{ Form::label('Send Reminders Till:') }}

					{{ Form::date('end_date', null, ['class'=>'form-control', 'required']) }}
					
				</div>

				<div class="form-group">

					{{ Form::submit('Create Reminder', ['class'=>'btn btn-success btn-block', 'style' => 'margin-top: 30px;']) }}
					
				</div>

            {!! Form::close() !!}

      	</div>

	</div>

</div>

@endsection

@section('footer_scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script type="text/javascript">

	$(".select2").select2({
	        placeholder: 'Select a Client', 
	        allowClear: true
	});

	$(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });

</script>

<script type="text/javascript">
	
	$("#client_id").change(function() {

	  var client_id = this.value;

		$.ajax({
			type: 'get',
			url: '{!! URL::to('getClientInfo') !!}',
			data: {'client_id':client_id},
			success:function(data) {

				$('#mobile').val(data.mobile);
				$('#email').val(data.email);
				$("#client-name").val(data.name);

			},
			error:function(){

				alert('Error! Client not found!');

			}

		});

	});


	function addNumber() {
            
        var html = '<div class="form-group"> <div class="row"> <div class="col-md-10"> {{ Form::number('mobile[]', null, ['id' => 'mobile', 'placeholder' => 'Mobile' ,'class'=>'form-control']) }} </div> <div class="col-md-2"> <button type="button" class="btn btn-danger" id="remove-number">- Remove </button> </div> </div> </div>';

        $('#number-container').append(html);

        $('#number-container').on('click', '#remove-number', function(e){
            $(this).parent('div').parent('div').remove();
            removeIndex.push(Number(this.name));
        });
    }

    function addEmail() {
            
        var html = '<div class="form-group"> <div class="row"> <div class="col-md-10"> {{ Form::email('email[]', null, ['id' => 'email', 'placeholder' => 'Email', 'class'=>'form-control']) }} </div> <div class="col-md-2"> <button type="button" class="btn btn-danger" id="remove-email">- Remove </button> </div> </div> </div>';

        $('#email-container').append(html);

        $('#email-container').on('click', '#remove-email', function(e){
            $(this).parent('div').parent('div').remove();
            removeIndex.push(Number(this.name));
        });
    }

    function addTemplate(elem) {

    	if(elem.id == 'template-1') {

    		var html = 'Dear Client,\nNAME has requested you to pay the lawyer\'s second installment on the DATE. ';

    	} else {

    		var html = 'Dear Client,\nNAME has requested you to pay the ECA fees on the DATE';

    	}

    	$('#message').val(html);

    }




</script>

@endsection