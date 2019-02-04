@extends('layouts.master')

@section('title', 'Profit and Loss')

@section('content')

@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

@stop

<div class="container-fluid">

	<div class="panel">

		<div class="panel-body">

			<h2>Profit and Loss Statement</h2>

		</div>

		<div class="panel-footer">

			<table id="current_clients" class="table table-striped table-bordered" style="width:100%">

            <thead>

				<tr>

                    <th>Date</th>
                    <th>Client Code</th>
                    <th>Client Name</th>
					<th>Location</th>
					<th>Description</th>
					<th>Type</th>
					<th>Bank</th>
                    <th>Notes</th>
					<th>Amount</th>

				</tr>

            </thead>

            <tbody>


				@foreach($reports as $key => $value)
				  <tr>
                    <td>{{ $value['date'] }}</td>
                    <td>{{ $value['client_code'] }}</td>
                    <td>{{ $value['client_name'] }}</td>
				    <td>{{ ucfirst($value['location']) }}</td>
				    <td>{{ $value['description'] }}</td>
				    <td>{{ $value['type'] }}</td>
                    <td>{{ $value['bank'] }}</td>
				    <td>{{ $value['notes'] }}</td>
				    <td>{{ number_format($value['amount']) }}</td>
				  </tr>
			  @endforeach


            </tbody>

            <tfoot>

               <tr>

                    <th>Date</th>
                    <th>Client Code</th>
                    <th>Client Name</th>
					<th>Location</th>
					<th>Description</th>
					<th>Type</th>
					<th>Bank</th>
                    <th>Notes</th>
					<th>Amount</th>

               </tr>

            </tfoot>

         </table>

		</div>

	</div>

</div>


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

<script>

   $(document).ready( function () {

       var table = $('#current_clients').DataTable({

       	dom: 'Bfrtip',
        buttons: [
            'csv',
            'excel',
            {
                extend: 'print',
                text: 'Print all (not just selected)',
                exportOptions: {
                    modifier: {
                        selected: null
                    }
                }
            },
            {
                text: 'Select all',
                action: function () {
                    table.rows().select();
                }
            },
            {
                text: 'Select none',
                action: function () {
                    table.rows().deselect();
                }
            }
        ],
        select: true
    } );
} );

</script>



@endsection

