@extends('layouts.master')

@section('url', '/client/action/' . $client->id)

@section('title', 'Assign Rms')

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

@endsection

@section('content')

<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h2>:: Assigned Rms</h2>
		</div>
	</div>

	<div class="panel">
		<div class="panel-body">
			<table class="table table-striped">
			  <thead>
			    <tr>
			      <th scope="col">#</th>
			      <th scope="col">Name</th>
			      <th scope="col">Mobile</th>
			      <th scope="col">Email</th>
			    </tr>
			  </thead>
			  <tbody>
			  	@foreach($assigned_rms as $index => $assigned_rm)
			  		@foreach($assigned_rm->users as $user)
				    <tr>
				      <th scope="row">{{ $index + 1 }}</th>
				      <td>{{ $user->name }}</td>
				      <td>{{ $user->mobile }}</td>
				      <td>{{ $user->email }}</td>
				    </tr>
				    @endforeach
			    @endforeach
			  </tbody>
			</table>							
		</div>
	</div>

	<div class="panel">
		<div class="panel-body">
			<h3>:: Add Rms</h3>

			{{ Form::open(['route'=>['client.rm.store', $client->id]]) }}
				<div class="form-group">
					<div class="row">
						<div class="col-md-12">
							<select id="rm" class="select2 form-control" name="rm_one" required>
							@foreach($rms as $rm)
								<option value="{{ $rm->id }}">{{ $rm->name }}</option>
							@endforeach
							</select>

							@if ($errors->has('user_type'))
							<span class="help-block">
								<strong>{{ $errors->first('user_type') }}</strong>
							</span>
							@endif
						</div>

						{{-- <div class="col-md-1">
							<button type="button" onclick="addRm()" class="btn btn-sm btn-success button4">+ Add More</button>
						</div> --}}
					</div>
				</div>
				<div id="rm-container"></div>
				<div class="row">
					<div class="container-fluid">
						<input type="submit" name="" value="Submit" class="btn btn-block btn-primary button2" style="margin-top: 20px">
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@endsection

@section('footer_scripts')
<script>

	$(document).ready(function() {
		$('.select2').select2();
	});

    function addRm() {

        var html = '<div class="row" style="margin-top:20px"><div class="form-group"><div class="col-md-9"> <select id="rm" class="form-control" name="rm[]" required> @foreach($rms as $rm) <option value="{{ $rm->id }}">{{ $rm->name }}</option> @endforeach </select> @if ($errors->has("user_type")) <span class="help-block"> <strong>{{ $errors->first("user_type") }}</strong> </span> @endif </div> <div class="col-md-1"> <button type="button" onclick="addRm()" class="btn btn-sm btn-success button4">+ Add More</button> </div> <div class="col-md-1"> <button type="button" id="removeRm" class="btn btn-sm btn-danger button4" style="margin-left: 10px">Remove</button> </div> </div></div>';

        $('#rm-container').append(html);

        $('#rm-container').on('click', '#removeRm', function(e){
            $(this).parent('div').parent('div').remove();
            removeIndex.push(Number(this.name));
        });
    }
    
</script>
@endsection