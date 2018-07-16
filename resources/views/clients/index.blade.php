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
                  <!-- <th>Action</th> -->
                  <th>Action</th>
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
                  <!-- <td>
                     <a href="{{ route('client.programs', ['client_id'=> $client->id ]) }}" class="btn btn-info button4">
                     View Programs
                     </a>
                  </td> -->
                  <td>
                     <a href="{{ route('client.counsellor', $client->id) }}" class="btn btn-danger button4 view_data" id="{{ $client->id }}">Assign Counsellors</a>
                  </td>
                  <td>
                     <a href="{{ route('appointment.client', $client->id) }}" class="btn btn-outline-warning button4">Set Appointment</a>
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
                     {!! Form::open(['route' => ['client.counsellor', $client->id]]) !!}
                     	<div class="form-group">
                     		{!! Form::label('counsellor', 'Counsellor:') !!}
                     	</div>
                     <ul id="list"></ul>
                  </div>
                  <div class="modal-body">
                    <div class="form-group">
                      <label for="rm" class="control-label">Counsellors:</label>

                      <div class="row">
                        <div class="col-xs-7">
                          <select id="counsellor_one" class="form-control" name="counsellor_one" required> 
                            @foreach($counsellors as $counsellor)
                              <option value="{{ $counsellor->id }}">{{ $counsellor->name }}</option>
                            @endforeach
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
<script>
 // $(document).ready(function(){  
      // $('.view_data').click(function(){  
      	   // var list = '';
          //  var client_id = $(this).attr("id");  
          //  var ul = document.getElementById("list");

           // $.ajax({  
           // 		type: 'get',
           // 		url: '{!!URL::to('getClientCounsellors')!!}',
           //      data: {'client_id': client_id},  
           //      success:function(data) {  
           //          if(data.length > 0) {
           //          	for (var i = 0; i < data.length; i++) {
           //          		list += '<li>' + data[i].name + '</li>';
           //          	}

           //          } else {
           //            list += '<li>No Counsellors Assigned!</li>';
           //          }

           //          ul.innerHTML = list;
           //          $('#client-counsellors').modal("show");
           //      }  
           // });     
         // ul.innerHTML = '';
      // });  
  // });  

 // function addCounsellors() {
 //    var html = '<div class="form-group"><div class="col-xs-7"> {!! Form::text("product_type", "asdf", ["class"=>"form-control"]) !!}<select id="counsellor" class="form-control" name="counsellor[]"> @foreach($counsellors as $counsellor) <option value="{{ $counsellor->id }}">{{ $counsellor->name }}</option> @endforeach </select></div> <div class="col-xs-2"> <button type="button" onclick="addCounsellors()" class="btn btn-sm btn-success button4">+ Add More</button> </div> <div class="col-xs-2"> <button type="button" id="removeCounsellor" class="btn btn-sm btn-danger button4" style="margin-left: 10px">Remove</button> </div> </div>';

 //    $('#counsellor-container').append(html);

 //    $('#counsellor-container').on('click', '#removeCounsellor', function(e){
 //        $(this).parent('div').parent('div').remove();
 //        removeIndex.push(Number(this.name));
 //    });
 // }

</script>

@endsection