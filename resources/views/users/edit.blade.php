@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible">
                    
                    {{ session()->get('message') }}
                    
                </div>
            @endif
            
            <div class="panel panel-default">
                <div class="panel-heading">Register Client</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('client.update.ind') }}">
                        {{ csrf_field() }}

                        {{-- Hidden fields --}}

                        <input type="hidden" name="client_id" value="{{ $client->id }}">

                        {{--  --}}

                        <div class="form-group">
                            <label for="client_code" class="col-md-3 control-label">Client ID</label>

                            <div class="col-md-6">
                                <input id="client_code" type="text" class="form-control" name="client_code" value="{{ $client->client_code }}" required autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-3 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $client->name }}" required autofocus>

                                
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('spouse-name') ? ' has-error' : '' }}">
                            <label for="spouse_name" class="col-md-3 control-label">Spouse Name</label>

                            <div class="col-md-6">
                                <input id="naspouse_nameme" type="text" class="form-control" name="spouse_name" value="{{ $client_add ? $client_add->spouse_name : '' }}" autofocus>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="col-md-3 control-label">Mobile (Primary)</label>

                            <div class="col-md-6">
                                <input id="mobile" type="number" class="form-control" name="mobile" value="{{ $client->mobile }}" required autofocus>
                            </div>

                            <div class="col-md-1">
                                <button type="button" onclick="addNumber()" class="btn btn-sm btn-success">+ Add More</button>
                            </div>
                        </div>

                        @if($adddional_numbers)
                        	@foreach($adddional_numbers as  $index => $adddional_number)

                        	<div class="form-group">
							   <label for="number" class="col-md-3 control-label">Mobile {{ $index + 2 }}</label> 
							   <div class="col-md-6"> <input id="number" class="form-control" name="numbers[]" value="{{$adddional_number->mobile}}" > </div>
							   
							</div>

							@endforeach

                        @endif

                        <div id="number-container"></div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-3 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ $client->email }}" required>

                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-3 control-label">Address</label>

                            <div class="col-md-6">
                                <textarea id="address" class="form-control" name="address" required>{{ $client_add ? $client_add->address : '' }}
                                </textarea>

                            </div>
                        </div>

                        @if(Auth::user()->user_role == 'admin')

                        <div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
                            <label for="status" class="col-md-3 control-label">Status</label>

                            <div class="col-md-6">

                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="successful">Successful</option>
                                    <option value="closed">Closed</option>
                                    <option value="canceled">Canceled</option>
                                </select>

                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-3 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-3 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        @else

                        <input type="hidden" name="status" value="active">

                        @endif

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-block button3">
                                    Update
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')
    <script>

        $('div.checkbox-group.required :checkbox:checked').length > 0
       

        function addNumber() {
            
            var html = '<div class="form-group"> <label for="number" class="col-md-3 control-label">Mobile</label> <div class="col-md-6"> <input id="number" class="form-control" name="numbers[]" > </div> <div class="col-md-1"> <button type="button" onclick="addNumber()" class="btn btn-sm btn-success">+ Add More</button> </div> <div class="col-md-1"> <button type="button" id="removeNumber" class="btn btn-sm btn-danger" style="margin-left: 10px">Remove</button> </div> </div>';

            $('#number-container').append(html);

            $('#number-container').on('click', '#removeNumber', function(e){
                $(this).parent('div').parent('div').remove();
                removeIndex.push(Number(this.name));
            });
        }
        
    </script>
@endsection
