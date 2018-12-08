@extends('layouts.master')

@section('url', '/myprograms/' . $client->id)

@section('title', 'My Tasks')

@section('header_scripts')
<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script>
   $(document).ready( function () {
       $('#tasks').DataTable({
         'columnDefs' : [
            {
               'searchable' : false,
               'targets' : [4,5]
            }
         ]
       });
   } );
</script>
@stop

@section('content')
<div class="container-fluid">

  {{-- Profile and Cover image --}}

  <div class="panel">

    <div class="panel-footer">

      <div class="profile-header">

        <div class="profile-main">

          <img src="{{ asset('img/blank-dp.png') }}" class="img-circle" alt="Avatar" height="100">

          <a href="{{ url('client/action/' . $client->id) }}"><h3 class="name">{{ $client->name }}</h3></a>

        </div>

      </div>

    </div>
    
  </div>

   <div class="panel">

      <div class="panel-heading">

         <h3 class="panel-title">Step Lists</h3>

         @if(Auth::user()->user_role != 'client')

           <div class="right">

            @if($client->status == 'active')

              <a href="#" type="button" class="btn btn-success button2" data-toggle="modal" data-target="#addStep">Add Step</a>

            @endif

           </div>

         @endif

      </div>
      
      <div class="panel-body">
        @if($assigned_steps->steps)
         <table class="table table-striped">
          <thead>
            <tr>
              <th>SL.</th>
              <th>Step Name</th>
              <th></th>
            </tr>
          </thead>
          <tbody>

            @forelse(json_decode($assigned_steps->steps, true) as $index => $step)
            @if(App\Step::find($step))
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ App\Step::find($step)->step_name}}</td>
              <td>
                  <a href="{{ route('client.mytasks', [$step, $client->id]) }}">
                  <button class="btn btn-info btn-block button2">View Tasks</button>
               </a>
             </td>
            </tr>
            @endif
            @empty
            <tr>
               <td>No assigned steps</td>
            </tr>
            @endforelse

            @else 

            <p>NO STEPS ASSIGNED!!!</p>

            @endif
          </tbody>
        </table>
      </div>
   </div>
</div>

<div id="addStep" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Step</h4>
      </div>
      <div class="modal-body">
        {!! Form::open(['route'=>['client.step.store', $program->id, $client->id]]) !!}
          {{ Form::label('Step Name:') }}
          {{ Form::select('step_id', $steps->pluck('step_name', 'id'), null, ['class'=>'form-control']) }}
        
      </div>
      <div class="modal-footer">
        {{ Form::submit('Add', ['class'=>'btn btn-success button2']) }}
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@endsection