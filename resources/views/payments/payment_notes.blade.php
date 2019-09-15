@extends('layouts.master')
@section('title', 'Payment Notes')
@section('content')
@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>

$(document).ready( function () {
	$('#payment-notes').DataTable();
});

</script>

@stop

<div class="container-fluid">

	<div class="panel">
		<div class="panel-body">
			<h1>{{ $client->name }}</h1>
		</div>

		<div class="panel-footer">

			@if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'operation')

        	<a href="#" data-toggle="modal" data-target="#addNotes" class="btn btn-success pull-right button2" style="margin: 10px">Add Note</a>

        	@endif

			<table id="payment-notes" class="table table-striped table-bordered" style="width:100%">

	            <thead>
	               <tr>
	                  <th>Date</th>
	                  <th>Description</th>
	                  <th>Amount</th>
	                  @if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'operation')

	                  	<th class="text-center">Edit</th>
	                  	<th class="text-center">Delete</th>

	                  @endif
	               </tr>
	            </thead>

	            <tfoot>
	               <tr>
	                  <th>Date</th>
	                  <th>Description</th>
	                  <th>Amount</th>

	                  @if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'operation')

	                  	<th class="text-center">Edit</th>
	                  	<th class="text-center">Delete</th>

	                  @endif
	               </tr>
	            </tfoot>

	            <tbody>
	            	@foreach($notes as $note)
	            	<tr>
	            		<td>{{ $note->date }}</td>
	            		<td>{{ $note->description }}</td>
	            		<td>{{ number_format($note->amount) }}</td>

	            		@if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'operation')

	            		<td>
	            			<button type="button" class="btn btn-defualt btn-block" id="{{ $note->id }}" onclick="editNote(this)"><span class="fa fa-edit"></span></button>
	            		</td>
	            		<td>
	            			<button type="button" class="btn btn-danger btn-sm btn-block" id="{{ $note->id }}" onclick="deletePrompt(this)"><span class="fa fa-trash fa-xs"></span></button>
	            		</td>

	            		@endif
	            	</tr>
	            	@endforeach
	            </tbody> 

	         </table>
		</div>
	</div>
</div>

<div id="addNotes" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Note</h4>
      </div>
      <div class="modal-body">
        {{ Form::open(['route'=>'payment.store.notes']) }}

          {{-- Hidden Fields --}}

          	{{ Form::hidden('client_id', $client->id) }}

          {{-- End of hidden fields --}}

          <div class="form-group">
            {{ Form::label('Date:') }}
            {{ Form::date('date', Carbon\Carbon::now(), ['class'=>'form-control', 'required']) }}
          </div>

          <div class="form-group">
            {{ Form::label('Description:') }}
            {{ Form::textarea('description', null, ['class'=>'form-control', 'rows'=>'3', 'required']) }}
          </div>

          <div class="form-group">
            {{ Form::label('Amount:') }}
            {{ Form::number('amount', null, ['class'=>'form-control', 'required']) }}
          </div>

      </div>

      <div class="modal-footer">

        <button type="submit" class="btn btn-success">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

        {{ Form::close() }}

      </div>

    </div>


  </div>
</div>

<div id="deletePrompt" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Warning</h4>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete this note?</p>
      </div>
      {{ Form::open(['route'=>'payment.delete.note']) }}
      <div class="modal-footer">
      	{{ Form::hidden('note_id', null, ['id'=>'note-id']) }}
        <button type="submit" class="btn btn-danger">Yes</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">No</button>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>

<div id="editNote" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Note</h4>
      </div>
      {{ Form::open(['route'=>'payment.edit.note']) }}

      <div class="modal-body">

      	{{-- Hidden Fields --}}

      		{{ Form::hidden('note_id', null, ['id'=>'id']) }}

      	{{-- End of hidden fields --}}
        
      	<div class="form-group">
      		
      		{{ Form::label('date') }}
      		{{ Form::date('date', null, ['class'=>'form-control', 'id'=>'date']) }}

      	</div>

      	<div class="form-group">
      		
      		{{ Form::label('description') }}
      		{{ Form::textarea('description', null, ['class'=>'form-control', 'rows'=>'3', 'id'=>'description']) }}

      	</div>

      	<div class="form-group">
      		
      		{{ Form::label('amount') }}
      		{{ Form::number('amount', null, ['class'=>'form-control', 'id'=>'amount']) }}

      	</div>

      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-info">Edit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>

      {{ Form::close() }}
    </div>
  </div>
</div>

@endsection

@section('footer_scripts')

<script type="text/javascript">
	
	function deletePrompt(elem) {
		document.getElementById('note-id').value = elem.id;
		$('#deletePrompt').modal('show');
	}

	function editNote(elem) {
		var note_id = elem.id;

		$.ajax({
			type : 'get',
			url: '{!! URL::to('findNoteInfo') !!}',
			data: {'note_id':note_id},
			success:function(data) {
				document.getElementById('id').value = data.id;
				document.getElementById('date').value = data.date;
				document.getElementById('description').value = data.description;
				document.getElementById('description').value = data.description;
				document.getElementById('amount').value = data.amount;
			},

		});

		$('#editNote').modal('show');
	}

</script>

@endsection