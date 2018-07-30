@extends('layouts.master')

@section('title', 'Tasks')

@section('content')
<div class="container-fluid">
	<div class="panel panel-headline">
		<div class="panel-body">
			<h3 class="text-center sub-header-padding">Set Target:</h3>
			<br>
			{{ Form::open(['route'=>['store.target', $records->first()->user_id]]) }}
				<div class="row">
					<div class="col-md-6">
						<div class="input-group">
                     		<span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     		{!! Form::text('target', null, ['class'=>'form-control', 'placeholder'=>'Target']) !!}
                  		</div>
					</div>

					<div class="col-md-6">
						<div class="input-group">
                     		<span class="input-group-addon"><i class="fa fa-globe"></i></span>
                     		<input type="month" class="form-control" name="month_year">
                  		</div>
					</div>

					<div class="col-md-12">
						{{ Form::submit('Submit', ['class'=>'btn btn-primary btn-block', 'style'=>'margin-top: 30px; border-radius: 20px;']) }}
					</div>

					{{ Form::close() }}
				</div>
			</div>
		</div>	


   <div class="panel">
      <div class="panel-body">
         <h3 class="sub-header-padding">Target Histroy: {{ App\User::find($records->first()->user_id)->name }} </h3>
         <br>
         <table class="table table-striped table-bordered">
            <thead>
               <tr>
                  <th>SL.</th>
                  <th>Month-Year</th>
                  <th>Target</th>
               </tr>
            </thead>
            <tbody>
            	@foreach($records as $index => $record)
                <tr>
               	   <td>{{ $index + 1 }}</td>
               	   <td>{{ Carbon\Carbon::parse($record->month_year)->format('F Y') }}</td>
               	   <td>{{ $record->target }}</td>
                </tr>
               @endforeach
            </tbody>
         </table>
      </div>
   </div>
</div>
@endsection