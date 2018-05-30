@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            @if(session()->has('message'))
            <div class="alert alert-success alert-dismissible">
                
                    {{ session()->get('message') }}
                
            </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">Register Client</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('user.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="client_code" class="col-md-3 control-label">Client ID</label>

                            <div class="col-md-6">
                                <input id="client_code" type="text" class="form-control" name="client_code" value="{{ $client_code }}" required autofocus readonly>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-3 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="col-md-3 control-label">Mobile</label>

                            <div class="col-md-6">
                                <input id="mobile" type="number" class="form-control" name="mobile" value="" required autofocus>
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-3 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
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

                        <div class="form-group">
                            <label for="programs" class="col-md-3 control-label">Choose Programs</label>

                            <div class="col-md-6">
                                @foreach($programs as $program)
                                    <div class="col-md-6">
                                        <input type="checkbox" name="programs[]" value="{{ $program->id }}"> {{ $program->program_name }}
                                    </div>
                                @endforeach
                            </div>
                            
                        </div>

                        <div class="form-group">
                            <label for="rm" class="col-md-3 control-label">Relation Manager</label>

                            <div class="col-md-6">
                                <select id="rm" class="form-control" name="rm_one" required>
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
                            <div class="col-md-1">
                                <button type="button" onclick="addRm()" class="btn btn-sm btn-success">+ Add More</button>
                            </div>
                        </div>

                        <div id="rm-container"></div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button type="submit" class="btn btn-primary btn-block button3">
                                    Register
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
        function addRm() {

            var html = '<div class="form-group"> <label for="rm" class="col-md-3 control-label">Relation Manager</label> <div class="col-md-6"> <select id="rm" class="form-control" name="rm[]" required> @foreach($rms as $rm) <option value="{{ $rm->id }}">{{ $rm->name }}</option> @endforeach </select> @if ($errors->has("user_type")) <span class="help-block"> <strong>{{ $errors->first("user_type") }}</strong> </span> @endif </div> <div class="col-md-1"> <button type="button" onclick="addRm()" class="btn btn-sm btn-success">+ Add More</button> </div> <div class="col-md-1"> <button type="button" id="remove" class="btn btn-sm btn-danger" style="margin-left: 10px">Remove</button> </div> </div>';

            $('#rm-container').append(html);

            $('#rm-container').on('click', '#remove', function(e){
                $(this).parent('div').parent('div').remove();
                removeIndex.push(Number(this.name));
            });
        }
        
    </script>
@endsection
