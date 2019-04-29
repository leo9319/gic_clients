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
                <div class="panel-heading">Edit Profile</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('user.update') }}"  enctype="multipart/form-data">
                        {{ csrf_field() }}

                        {{-- Hidden fields --}}

                        <input type="hidden" name="client_id" value="{{ $user->id }}">

                        {{--  --}}


                        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-3 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ $user->name }}" required autofocus>

                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="mobile" class="col-md-3 control-label">Mobile (Primary)</label>

                            <div class="col-md-6">
                                <input id="mobile" type="number" class="form-control" name="mobile" value="{{ $user->mobile }}" required autofocus>
                            </div>

                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-3 control-label">Email</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ $user->email }}" required>

                            </div>
                        </div>

                        <div class="form-group">
                            <label for="profile_picture" class="col-md-3 control-label">Profile Picture</label>

                            <div class="col-md-6">
                                <input type="file" class="custom-file-input" name="profile_picture">
                            </div>
                        </div>

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

<script type="text/javascript">
    
    $(function(){
      $(':input[type=number]').on('mousewheel',function(e){ $(this).blur(); });
    });

</script>

@endsection