@extends('layouts.master')

@section('title', 'Clients')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready(function() {
   	$('#clients').DataTable();
   });
</script>
@stop

@section('content')
<div class="container-fluid">
   <div class="panel">
      <div class="panel-body">
         <table id="clients" class="table table-striped" style="width:100%">
            <thead>
               <tr>
                  <th>Client ID.</th>
                  <th>Name</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Action</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               @foreach($clients as $index => $client)
               <tr>
                  <td>{{ $client->client_code }}</td>
                  <td>{{ $client->name }}</td>
                  <td>{{ $client->mobile }}</td>
                  <td>{{ $client->email }}</td>
                  <td>
                     <a href="{{ route('client.programs', ['client_id'=> $client->id ]) }}" class="btn btn-info button4">
                     View Programs
                     </a>
                  </td>
                  <td>
                     <button class="btn btn-success button4 view_data" id="{{ $client->id }}">Assign Counsellors</button>
                  </td>
               </tr>
               @endforeach
            </tbody>
         </table>

         <div id="client-counsellors" class="modal fade" role="dialog">
            <div class="modal-dialog">
               <!-- Modal content-->
               <div class="modal-content">
                  <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Assign Counsellors</h4>
                  </div>
                  <div class="modal-body">
                     {!! Form::open() !!}
                     	<div class="form-group">
                     		{!! Form::label('counsellor', 'Counsellor:') !!}
                     	</div>
                     {!! Form::close() !!}
                     <p id="client_name"></p>
                     <ul id="list"></ul>
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
@endsection

@section('footer_scripts')
<script>
 $(document).ready(function(){  
      $('.view_data').click(function(){  
      	   var list = '';
           var client_id = $(this).attr("id");  
           var ul = document.getElementById("list");

           $.ajax({  
           		type: 'get',
           		url: '{!!URL::to('getClientCounsellors')!!}',
                data: {'client_id': client_id},  
                success:function(data) {  
                    if(data.length > 0) {
                    	for (var i = 0; i < data.length; i++) {
                    		list += '<li>' + data[i].name + '</li>';
                    	}

                    	ul.innerHTML = list;
                    }

                    $('#client-counsellors').modal("show");
                }  
           });     
           ul.innerHTML = '';
        });  
     });  
</script>

@endsection