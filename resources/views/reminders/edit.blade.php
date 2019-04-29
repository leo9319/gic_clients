@extends('layouts.master')

@section('title', 'Reminders')

@section('content')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />


@stop

<div class="container-fluid">

  <div class="panel">

    <div class="panel-body">

		  <h2>Edit Reminder</h2>

		</div>

		<div class="panel-footer">

      {!! Form::open(['route' => ['reminders.update', $reminder->id], 'method'=> 'PUT']) !!}

        <div class="form-group">

          {{ Form::label('Client Name:') }}

          {{ Form::select('client_id', $clients->pluck('name', 'id'), $reminder->client_id, ['id' => 'client_id', 'class'=>'form-control select2']) }}

        </div>

        @foreach(json_decode($reminder->mobile) as $mobile)

          <div class="form-group">

            {{ Form::label('Client Phone:') }}


            {{ Form::number('mobile[]', $mobile, ['id' => 'mobile', 'placeholder' => 'Mobile' ,'class'=>'form-control']) }}
              
            
          </div>

        @endforeach


        @foreach(json_decode($reminder->email) as $email)

          <div class="form-group">

            {{ Form::label('Client email:') }}


            {{ Form::email('email[]', $email, ['id' => 'mobile', 'placeholder' => 'Email' ,'class'=>'form-control']) }}

            
          </div>

        @endforeach


        <div class="form-group">

          {{ Form::label('message:') }}

          {{ Form::textarea('message', $reminder->message, ['id' => 'message', 'class' => 'form-control', 'rows' => '4', 'required']) }}
          
        </div>

        <div class="form-group">

          {{ Form::label('Send Reminders Till:') }}

          {{ Form::date('end_date', $reminder->end_date, ['class'=>'form-control', 'required']) }}
          
        </div>

        <div class="form-group">

          {{ Form::label('Status:') }}

          <label class="fancy-radio">
          {{ Form::radio('status', 1, $reminder->status) }}
          <span><i></i>Active</span>
          </label>
          <label class="fancy-radio">
          {{ Form::radio('status', 0, $reminder->status ? false: true) }}
          <span><i></i>Inactive</span>
          </label>
          
        </div>

        <div class="form-group">

          {{ Form::submit('Update Reminder', ['class'=>'btn btn-success btn-block', 'style' => 'margin-top: 30px;']) }}
          
        </div>

            {!! Form::close() !!}

        </div>
	</div>
</div>



@section('footer_scripts')

<script type="text/javascript">

  $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });

</script>

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>

@endsection

@endsection