@extends('layouts.master')

@section('url', '/client/action/' . $client->id)

@section('title', 'Clients')

@section('header_scripts')

@section('content')

<div class="container-fluid">
	<div class="panel">
		<div class="panel-body">
			<h2>:: Assigned Counselors</h2>
		</div>
		<div class="panel-footer">
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
			  	@foreach($assigned_councelors as $index => $assigned_councelors)
			  		@foreach($assigned_councelors->users as $user)
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
			<h3>:: Add Councelors</h3>

			{{ Form::open(['route'=>['client.counsellor.store', $client->id]]) }}
				<div class="form-group">
					<div class="row">
						<div class="col-md-9">
							<select id="counsellor" class="form-control" name="counsellor_one" required>
							@foreach($counselors as $counselor)
								<option value="{{ $counselor->id }}">{{ $counselor->name }}</option>
							@endforeach
							</select>

							@if ($errors->has('user_type'))
							<span class="help-block">
								<strong>{{ $errors->first('user_type') }}</strong>
							</span>
							@endif
						</div>

						<div class="col-md-1">
							<button type="button" onclick="addCounsellor()" class="btn btn-sm btn-success button4">+ Add More</button>
						</div>
					</div>
				</div>
				<div id="counsellor-container"></div>
				<div class="row">
					<div class="container-fluid">
						<input type="submit" name="" value="Submit" class="btn btn-block btn-primary button4" style="margin-top: 20px">
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>

@endsection

@section('footer_scripts')
<script>
    function addCounsellor() {

        var html = '<div class="row" style="margin-top:20px"><div class="form-group"><div class="col-md-9"> <select id="counsellor" class="form-control" name="counsellor[]" required> @foreach($counselors as $counsellor) <option value="{{ $counsellor->id }}">{{ $counsellor->name }}</option> @endforeach </select> @if ($errors->has("user_type")) <span class="help-block"> <strong>{{ $errors->first("user_type") }}</strong> </span> @endif </div> <div class="col-md-1"> <button type="button" onclick="addCounsellor()" class="btn btn-sm btn-success button4">+ Add More</button> </div> <div class="col-md-1"> <button type="button" id="removeCounsellor" class="btn btn-sm btn-danger button4" style="margin-left: 10px">Remove</button> </div> </div></div>';

        $('#counsellor-container').append(html);

        $('#counsellor-container').on('click', '#removeCounsellor', function(e){
            $(this).parent('div').parent('div').remove();
            removeIndex.push(Number(this.name));
        });
    }
    
</script>
@endsection