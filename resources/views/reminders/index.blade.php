@extends('layouts.master')

@section('title', 'Reminders')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />

<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
$(function() {

  var $tableSel = $('#reminders');
  $tableSel.dataTable({
    "ordering": false,
     dom: 'Bfrtip',
        buttons: [
            'csv',
            'excel',
            {
                extend: 'print',
                text: 'Print all (not just selected)',
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                }
            },
            {
                text: 'Select all',
                action: function () {
                    this.rows().select();
                }
            },
            {
                text: 'Select none',
                action: function () {
                    this.rows().deselect();
                }
            }
        ],
        select: true
  });
  
  $('#filter').on('click', function(e){
    e.preventDefault();
    var startDate = $('#start').val(),
        endDate = $('#end').val();
    
    filterByDate(0, startDate, endDate); // We call our filter function
    
    $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
  });
  
  // Clear the filter. Unlike normal filters in Datatables,
  // custom filters need to be removed from the afnFiltering array.
  $('#clearFilter').on('click', function(e){
    e.preventDefault();
    $.fn.dataTableExt.afnFiltering.length = 0;
    $tableSel.dataTable().fnDraw();
  });
  
});

var filterByDate = function(column, startDate, endDate) {
  // Custom filter syntax requires pushing the new filter to the global filter array
    $.fn.dataTableExt.afnFiltering.push(
        function( oSettings, aData, iDataIndex ) {
          var rowDate = normalizeDate(aData[column]),
              start = normalizeDate(startDate),
              end = normalizeDate(endDate);

          
          // If our date from the row is between the start and end
          if (start <= rowDate && rowDate <= end) {
            return true;
          } else if (rowDate >= start && end === '' && start !== ''){
            return true;
          } else if (rowDate <= end && start === '' && end !== ''){
            return true;
          } else {
            return false;
          }
        }
    );
  };

  var normalizeDate = function(dateString) {
  var date = new Date(dateString);
  var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
  return normalized;
}
</script>

@stop

<div class="container-fluid">

  <div class="panel">

    <div class="panel-body">

		  <h2>Reminders</h2>

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

			<table border="0" cellspacing="5" cellpadding="5">

				<tbody>

  				<tr>

  					<td>Start Date: </td>

  					<td><input type="date" id="start" name="min" class="form-control"></td>

  				</tr>

  				<tr>

  					<td>End Date: </td>

  					<td><input type="date" id="end" name="max" class="form-control"></td>

  				</tr>

				</tbody>

			</table>

  		<hr>

  		<button id="filter" class="btn btn-success btn-sm">Filter</button>

  		<button id="clearFilter" class="btn btn-info btn-sm" class="btn btn-success">Clear Filter</button>

  		<hr>

	     <table id="reminders" class="table table-striped table-bordered" style="width:100%">

    	 <thead>

					<th>Date Created</th>

					<th>Client Code</th>

					<th>Client Name</th>

          <th>Mobile</th>

          <th>Email</th>

					<th>End Date</th>

          <th>Status</th>

					<th>Action</th>

          <th>Action</th>

      </thead>

      <tbody>

        @foreach($reminders as $reminder)

        <tr>
          
            <td>{{ Carbon\Carbon::parse($reminder->created_at)->format('d-M-y') }}</td>
            <td>{{ $reminder->user->client_code }}</td>
            <td>{{ $reminder->user->name }}</td>
            <td>{{ $reminder->mobile }}</td>
            <td>{{ $reminder->email }}</td>
            <td>{{ Carbon\Carbon::parse($reminder->end_date)->format('d-M-y') }}</td>
            <td>{{ $reminder->status ? 'Active': 'Inactive'}}</td>
            <td>
              <a href="{{ route('reminders.edit', $reminder->id) }}" class="btn btn-info edit">Edit</a>
            </td>
            <td>
              <button class="btn btn-danger remove" name="{{ $reminder->id }}">Delete</button>
            </td>


        </tr>

        @endforeach()

      </tbody>

      <tfoot>

				<th>Date Created</th>

        <th>Client Code</th>

        <th>Client Name</th>

        <th>Mobile</th>

        <th>Email</th>

        <th>End Date</th>

        <th>Status</th>

        <th>Action</th>

        <th>Action</th>

      </tfoot>

     </table>
		</div>
	</div>
</div>

<div class="modal fade" id="edit-reminder" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

  <div class="modal-dialog" role="document">

    <div class="modal-content">

      <div class="modal-header">

        <h5 class="modal-title" id="exampleModalLabel">Edit Reminder</h5>

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">

        <span aria-hidden="true">&times;</span>

        </button>

      </div>

      <div class="modal-body">

        {!! Form::open() !!}

          <div class="form-group">

            {{ Form::label('Client Code:') }}

            <br>

            {{ Form::select('client_id', $clients->pluck('name', 'id'), null, ['id' => 'client-id', 'class'=>'form-control', 'style'=>'width:550px']) }}

          </div>

          <div class="form-group">

            {{ Form::label('Client Phone(s):') }}

            {{ Form::number('mobile[]', null, ['id' => 'mobile', 'placeholder' => 'Mobile' ,'class'=>'form-control', 'required']) }}
                

          </div>

          <div class="form-group">

            {{ Form::label('Client Email(s):') }}

            {{ Form::number('email[]', null, ['id' => 'email', 'placeholder' => 'Mobile' ,'class'=>'form-control', 'required']) }}
                

          </div>

          <div class="form-group">

            {{ Form::label('Send Reminders Till:') }}

            {{ Form::date('end_date', null, ['class'=>'form-control', 'required']) }}
            
          </div>

          <div class="form-group">

            {{ Form::label('Status:') }}
            <br>

            {{ Form::radio('status', 1) }} Active
            <br>
            {{ Form::radio('status', 0) }} Inactive
            
          </div>
            
        </div>

      <div class="modal-footer">

        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

        {{ Form::submit('Update', ['class'=>'btn btn-primary']) }}

      </div>

      {!! Form::close() !!}

    </div>

  </div>

</div>



<div class="modal fade" id="remove-reminder" role="dialog">
  <div class="modal-dialog">

      <div class="modal-content">
          <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">Warning!</h4>
          </div>
          <div class="modal-body">
              <p>Are you sure you want to remove the reminder?</p>
          </div>
          <div class="modal-footer">

              {{ Form::open(['route'=>'reminders.delete']) }}

                {{ Form::hidden('reminder_id', null, ['id'=>'reminder-id']) }}

                {{ Form::submit('Yes', ['class'=>'btn btn-danger']) }}

              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>

              {{ Form::close() }}
          </div>
      </div>

    </div>
</div>

@section('footer_scripts')

<script type="text/javascript">
  
  $('.remove').on('click', function(){

    $('#reminder-id').val(this.name);

    $('#remove-reminder').modal();

  });



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