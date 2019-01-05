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
                    <form class="form-horizontal" method="POST" action="{{ route('user.store') }}">
                        {{ csrf_field() }}

                        <div class="form-group">
                            <label for="client_code" class="col-md-3 control-label">Client ID</label>

                            <div class="col-md-6">
                                <input id="client_code" type="text" class="form-control" name="client_code" value="{{ $client_code }}" required autofocus>
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

                        <div class="form-group{{ $errors->has('spouse-name') ? ' has-error' : '' }}">
                            <label for="spouse_name" class="col-md-3 control-label">Spouse Name</label>

                            <div class="col-md-6">
                                <input id="naspouse_nameme" type="text" class="form-control" name="spouse_name" value="{{ old('spouse_name') }}" autofocus>

                                @if ($errors->has('spouse-name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('spouse-name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="col-md-3 control-label">Mobile (Primary)</label>

                            <div class="col-md-6">
                                <input id="mobile" type="number" class="form-control" name="mobile" value="" required autofocus>
                            </div>

                            <div class="col-md-1">
                                <button type="button" onclick="addNumber()" class="btn btn-sm btn-success">+ Add More</button>
                            </div>
                        </div>

                        <div id="number-container"></div>

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

                        <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-3 control-label">Address</label>

                            <div class="col-md-6">
                                <textarea id="address" class="form-control" name="address" required>{{ old('address') }}</textarea>


                                @if ($errors->has('address'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('address') }}</strong>
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
                                <div class="checkbox-group required">
                                    @foreach($programs as $program)
                                        <div class="col-md-6" style="font-size: 9pt">
                                            <input type="checkbox" name="programs[]" value="{{ $program->id }}"> {{ $program->program_name }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                        </div>

                        {{-- <div class="form-group">
                            <label for="programs" class="col-md-3 control-label">Country of Choice</label>

                            <div class="col-md-6">
                                <div class="col-md-6">
                                    <input type="checkbox" name="country_of_choice[]" value="canada" checked> Canada
                                </div>
                                <div class="col-md-6">
                                    <input type="checkbox" name="country_of_choice[]" value="australia"> Australia
                                </div>
                            </div>
                            
                        </div> --}}

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
                            <label for="counselor" class="col-md-3 control-label">Counselor</label>

                            <div class="col-md-6">
                                <select id="counselor" class="form-control" name="counsellor_one" required>
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
                                <button type="button" onclick="addCounsellor()" class="btn btn-sm btn-success">+ Add More</button>
                            </div>
                        </div>

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

        $('div.checkbox-group.required :checkbox:checked').length > 0

        function addRm() {

            var html = '<div class="form-group"> <label for="rm" class="col-md-3 control-label">Relation Manager</label> <div class="col-md-6"> <select id="rm" class="form-control" name="rm[]" required> @foreach($rms as $rm) <option value="{{ $rm->id }}">{{ $rm->name }}</option> @endforeach </select> @if ($errors->has("user_type")) <span class="help-block"> <strong>{{ $errors->first("user_type") }}</strong> </span> @endif </div> <div class="col-md-1"> <button type="button" onclick="addRm()" class="btn btn-sm btn-success">+ Add More</button> </div> <div class="col-md-1"> <button type="button" id="remove" class="btn btn-sm btn-danger" style="margin-left: 10px">Remove</button> </div> </div>';

            $('#rm-container').append(html);

            $('#rm-container').on('click', '#remove', function(e){
                $(this).parent('div').parent('div').remove();
                removeIndex.push(Number(this.name));
            });
        }

        function addCounsellor() {

            var html = '<div class="form-group"> <label for="counselor" class="col-md-3 control-label">Counselor</label> <div class="col-md-6"> <select id="counselor" class="form-control" name="counselor[]" required> @foreach($counselors as $counselor) <option value="{{ $counselor->id }}">{{ $counselor->name }}</option> @endforeach </select> @if ($errors->has("user_type")) <span class="help-block"> <strong>{{ $errors->first("user_type") }}</strong> </span> @endif </div> <div class="col-md-1"> <button type="button" onclick="addCounsellor()" class="btn btn-sm btn-success">+ Add More</button> </div> <div class="col-md-1"> <button type="button" id="removeCounsellor" class="btn btn-sm btn-danger" style="margin-left: 10px">Remove</button> </div> </div>';

            $('#counselor-container').append(html);

            $('#counselor-container').on('click', '#removeCounsellor', function(e){
                $(this).parent('div').parent('div').remove();
                removeIndex.push(Number(this.name));
            });
        }

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
