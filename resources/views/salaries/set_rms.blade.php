@extends('layouts.master') 

@section('title', 'Set RMs') 

@section('content') 

@section('header_scripts')

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

@endsection

<div class="container-fluid">

    <div class="panel panel-headline">

        <div class="panel-heading">

            <h2>Set RMs</h2>

        </div>

        <div class="panel-body">

            <div class="row">

                <div class="col-md-8">

                    <table id="statement" class="table table-striped table-bordered" style="width:100%">

                        <thead>

                            <tr>

                                <th>SL.</th>

                                <th>Variable Name</th>

                                <th>Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>
                                <td>1</td>
                                <td>Designate Employees</td>
                                <td>
                                    <a href="{{ route('salaries.set_rms') }}" class="btn btn-success btn-block btn-sm">Assign</a>
                                </td>
                            </tr>

                        </tbody>

                        <tfoot>

                            <tr>

                                <th>SL.</th>

                                <th>Variable Name</th>

                                <th>Action</th>

                            </tr>

                        </tfoot>

                    </table>

                </div>

                <div class="col-md-4">

                    {{ Form::open(['route'=>'salaries.store_employees']) }}

                    {{-- Hidden Variables --}}

                    {{ Form::hidden('role', 'rm') }}

                    {{-- End of hidden variables --}}

                    <div class="form-group">

                        {{ Form::label('name:') }} {{ Form::text('employee_name', null, ['class'=>'form-control', 'required']) }}

                    </div>

                    <div class="form-group">

                        {{ Form::label('rm_profile:') }} {{ Form::select('rm_id', $rms->pluck('name', 'id'), null, ['class'=>'form-control select2']) }}

                    </div>

                    <div class="form-group">

                        {{ Form::label('counselor_profile:') }} {{ Form::select('counselor_id', $counselors->pluck('name', 'id'), null, ['class'=>'form-control select2']) }}

                    </div>

                    <div class="form-group">

                        {{ Form::submit('submit', ['class'=>'btn btn-success btn-block btn-sm']) }}

                    </div>

                    {{ Form::close() }}

                </div>

            </div>

        </div>

    </div>

</div>

@endsection 

@section('footer_scripts')
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

@endsection