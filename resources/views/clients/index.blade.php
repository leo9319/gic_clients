@extends('layouts.master')

@section('url', '/dashboard')

@section('title', 'All Clients')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready(function() {
   	$('#clients').DataTable({
    "order": [[ 0, "desc" ]],
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
            },
            'colvis'
        ],
        select: true
  });
   });
</script>
@stop

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-body">
         <table id="clients" class="table table-striped table-bordered" style="width:100%">
            <thead>
               <tr>
                  <th>Client ID.</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($clients as $index => $client)
               <tr>
                  <td><a href="{{ route('client.profile', ['client_id'=> $client->id ]) }}">{{ $client->client_code }}</a></td>
                  <td>{{ $client->name }}</td>
                  <td>{{ $client->mobile }}</td>
                  <td>{{ $client->email }}</td>
                  <td>{{ $client->status }}</td>
                  <td>
                     <a href="{{ route('client.action', $client->id) }}" class="btn btn-outline-warning button2">View Actions</a>
                  </td>
               </tr>
               @endforeach
            </tbody>
            <tfoot>
              <tr>
                  <th>Client ID.</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Status</th>
                  <th>Action</th>
               </tr>
            </tfoot>
         </table>

         <div id="client-counsellors" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Assign Counselors</h4>
                  </div>
                  <div class="modal-body">
                     {!! Form::open() !!}
                     	<div class="form-group">
                     		{!! Form::label('counselor', 'Counselor:') !!}
                     	</div>
                     <ul id="list"></ul>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="rm" class="control-label">Counsellors:</label>

                      <div class="row">
                        <div class="col-xs-7">
                          <select id="counsellor_one" class="form-control" name="counsellor_one" required> 
                            @forelse($counselors as $counselor)
                              <option value="{{ $counselor->id }}">{{ $counselor->name }}</option>
                            @empty
                            @endforelse
                        </select>
                        </div>

                        <div class="col-xs-2">
                            <button type="button" class="btn btn-sm btn-success button4">+ Add More</button>
                        </div>

                        <div id="counsellor-container">
                          {{ Form::text('test') }}
                        </div>
                      </div> 

                      <input type="submit" name="" class="btn btn-info btn-block button4" style="margin-top: 20px">  
                    </div>

                  </div>
                  <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
</div>
{!! Form::close() !!} 


@endsection


@section('footer_scripts')

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
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.colVis.min.js"></script>

@endsection