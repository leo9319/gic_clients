@extends('layouts.master') 
@section('title', 'Payment History') 
@section('content') 
@section('header_scripts')

<link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<script src="//cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>

<script>
    $(function() {

        var $tableSel = $('#payment-history');
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

        $('#filter').on('click', function(e) {
            e.preventDefault();
            var startDate = $('#start').val(),
                endDate = $('#end').val();

            filterByDate(0, startDate, endDate); // We call our filter function

            $tableSel.dataTable().fnDraw(); // Manually redraw the table after filtering
        });

        // Clear the filter. Unlike normal filters in Datatables,
        // custom filters need to be removed from the afnFiltering array.
        $('#clearFilter').on('click', function(e) {
            e.preventDefault();
            $.fn.dataTableExt.afnFiltering.length = 0;
            $tableSel.dataTable().fnDraw();
        });

    });

    var filterByDate = function(column, startDate, endDate) {
        // Custom filter syntax requires pushing the new filter to the global filter array
        $.fn.dataTableExt.afnFiltering.push(
            function(oSettings, aData, iDataIndex) {
                var rowDate = normalizeDate(aData[column]),
                    start = normalizeDate(startDate),
                    end = normalizeDate(endDate);

                // If our date from the row is between the start and end
                if (start <= rowDate && rowDate <= end) {
                    return true;
                } else if (rowDate >= start && end === '' && start !== '') {
                    return true;
                } else if (rowDate <= end && start === '' && end !== '') {
                    return true;
                } else {
                    return false;
                }
            }
        );
    };

    var normalizeDate = function(dateString) {
        var date = new Date(dateString);
        var normalized = date.getFullYear() + '' + (("0" + (date.getMonth() + 1)).slice(-2)) + '' + ("0" + date.getDate()).slice(-2);
        return normalized;
    }
</script>

@stop

<div class="container-fluid">
    <div class="panel">
        <div class="panel-body">
            <h2>Payment History</h2>
        </div>

        <div class="panel-footer">

            <table border="0" cellspacing="5" cellpadding="5">
                <tbody>
                    <tr>
                        <td>Start Date: </td>
                        <td>
                            <input type="date" id="start" name="min" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td>End Date: </td>
                        <td>
                            <input type="date" id="end" name="max" class="form-control">
                        </td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <button id="filter" class="btn btn-success btn-sm">Filter</button>
            <button id="clearFilter" class="btn btn-info btn-sm" class="btn btn-success">Clear Filter</button>
            <hr>

            <table id="payment-history" class="table table-striped table-bordered" style="width:100%">

                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Receipt ID</th>
                        <th>Client Code.</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Step</th>
                        <th>Invoice Amount</th>
                        <th>Total Paid</th>
                        <th>Due Amount</th>
                        <th>Note</th>
                        <th>View Details</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($payments as $payment)
                    @if($payment->stepInfo->targetSetting->counselor_count > 0)
                    @if($payment->totalAmount() == $payment->totalApprovedPayment->sum('amount_paid'))
                    @if(in_array($user_id, $payment->user->getAssignedCounselors->pluck('counsellor_id')->toArray()))
                    <tr>
                        <td>{{ Carbon\Carbon::parse($payment->created_at)->format('d-M-y') }}</td>
                        <td>{{ ucfirst($payment->location) }}</td>
                        <td>{{ $payment->receipt_id }}</td>
                        <td>
                            <a href="{{ route('client.profile', $payment->client_id) }}">
							{{ $payment->userInfo->client_code ?? 'Client Removed'}}
						</a>
                        </td>
                        <td>{{ $payment->userInfo->name ?? 'Client Removed'}}</td>
                        <td>{{ $payment->programInfo->program_name ?? 'Program Removed'}}</td>
                        <td>{{ $payment->stepInfo->step_name ?? 'Step Removed' }}</td>
                        <td>{{ number_format($payment->totalAmount()) }}</td>
                        <td>{{ number_format($payment->totalApprovedPayment->sum('amount_paid')) }}</td>
                        <td>{{ number_format($payment->totalAmount() - $payment->totalApprovedPayment->sum('amount_paid')) }}</td>
                        <td>{{ $payment->comments }}</td>
                        <td>
                            <a href="{{ route('payment.show', $payment->id) }}" class="btn btn-defualt btn-sm button2">View Payment</a>
                        </td>

                    </tr>
                    @endif
                    @endif
                    @endif
                    @endforeach

                </tbody>

                <tfoot>
                    <tr>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Receipt ID</th>
                        <th>Client Code.</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Step</th>
                        <th>Invoice Amount</th>
                        <th>Total Paid</th>
                        <th>Due Amount</th>
                        <th>Note</th>
                        <th>View Details</th>
                    </tr>
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