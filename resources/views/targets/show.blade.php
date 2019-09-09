@extends('layouts.master') 

@section('title', 'My Targets') 

@section('content') 

@section('header_scripts')


<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
    $(function() {

        var $tableSel = $('#my-targets');

        $tableSel.dataTable({

            "ordering": false,
            dom: 'Bfrtip',
            buttons: [
                'csv',
                'excel', {
                    extend: 'print',
                    text: 'Print all (not just selected)',
                    exportOptions: {
                        modifier: {
                            selected: null
                        }
                    }
                }, {
                    text: 'Select all',
                    action: function() {
                        this.rows().select();
                    }
                }, {
                    text: 'Select none',
                    action: function() {
                        this.rows().deselect();
                    }
                }
            ],
            select: true
        });

    });
</script>

@stop

<div class="container-fluid">

    <div class="panel">

        <div class="panel-body">

            <h2>Reminders</h2> 

            @if ($errors->any())

	            <div class="alert alert-danger">
	                <ul>
	                    @foreach ($errors->all() as $error)
	                    <li>{{ $error }}</li>
	                    @endforeach
	                </ul>
	            </div>

            @endif

        </div>

        <div class="panel-footer">

            <table id="reminders" class="table table-striped table-bordered" style="width:100%">

                <thead>

                	<th>Date Created</th>

                    <th>Client Code</th>

                    <th>Client Name</th>

                    <th>Mobile</th>

                    <th>Email</th>

                    <th>End Date</th>

                    <th>Status</th>

                    <th>Action</th>

                    <th>Action</th>

                </thead>

                <tbody>

                    <tr>

                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    </tr>

                </tbody>

                <tfoot>

                    <th>Date Created</th>

                    <th>Client Code</th>

                    <th>Client Name</th>

                    <th>Mobile</th>

                    <th>Email</th>

                    <th>End Date</th>

                    <th>Status</th>

                    <th>Action</th>

                    <th>Action</th>

                </tfoot>

            </table>
        </div>
    </div>
</div>

@section('footer_scripts')

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/select/1.2.7/js/dataTables.select.min.js"></script>

@endsection @endsection