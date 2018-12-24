<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>Payment Details</title>
  <link rel="stylesheet" href="{{ asset('css/payment.css') }}" media="all">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
</head>

<body>

<header class="clearfix">

  <div id="logo">

    <img src="{{ asset('img/logo2.png') }}">
  </div>

  <div id="company">

    <h2 class="name">Global Immigration Consultants Ltd.</h2>

    <div>56B (1st Floor, Rd 132, Dhaka 1212</div>
    <div>09678744223</div>
    <div><a href="{{ url('http://gicbd.com') }}">http://gicbd.com</a></div>
  </div>
</header>

<main>

<div id="details" class="clearfix">

  <div id="client">

    <div class="to">INVOICE TO:</div>

    <h2 class="name">{{ $client ? $client->name : 'Client Not Found!' }}</h2>
    <b>{{ $client ? $client->client_code : 'Client Code Not Found!' }}</b>
    <div class="address">{{ $client_file_info ? $client_file_info->address : 'Address Not Found!' }}</div>
    <div class="email">{{ $client ? $client->email : 'Email Not Found!' }}</div>

  </div>


  <div id="invoice">

    <h2>

      <table>
        <tr>
          <td>Program Name:</td>
          <td>{{ $program ? $program->program_name : 'Program Name Not Found!' }}</td>
        </tr>
        <tr>
          <td>Step Name:</td>
          <td>{{ $step ? $step->step_name : 'Step Name Not Found!' }}</td>
        </tr>
      </table>

    </h2>

  </div>
</div>

<table border="0" cellspacing="0" cellpadding="0">
  <thead>
     <tr>
        <th class="no">#</th>
        <th class="desc">PAYMENT DETAILS</th>
        <th></th>
        <th></th>
        <th class="unit">TOTAL AMOUNT</th>
     </tr>
  </thead>
  <tbody>
     <tr>
        <td class="no">01</td>
        <td class="desc">
           <h3>Initial Assessment Fee</h3>
        </td>
        <td></td>
        <td></td>
        <td class="unit">{{ number_format($payment->opening_fee) }}</td>

     </tr>
     <tr>
        <td class="no">02</td>
        <td class="desc">
           <h3>Lawyer Fees/ Service Charges</h3>
        </td>
        <td></td>
        <td></td>
        <td class="unit">{{ number_format($payment->embassy_student_fee) }}</td>

     </tr>
     <tr>
        <td class="no">03</td>
        <td class="desc">
           <h3>Government / Third party fees</h3>
        </td>
        <td></td>
        <td></td>
        <td class="unit">{{ number_format($payment->service_solicitor_fee) }}</td>

     </tr>
     <tr>
        <td class="no">04</td>
        <td class="desc">
           <h3>Other Fees</h3>
        </td>
        <td></td>
        <td></td>
        <td class="unit">{{ number_format($payment->other) }}</td>

     </tr>
  </tbody>
  <tfoot>
     <tr>
        <td colspan="2"></td>
        <td colspan="2">SUBTOTAL</td>
        <td>{{ number_format($total_amount) }}</td>
     </tr>
     <tr>
        <td colspan="2"></td>
        <td colspan="2">AMOUNT PAID</td>
        <td>{{ number_format($payment_types->sum('amount_paid')) }}</td>
     </tr>
     <tr>
        <td colspan="2"></td>
        <td colspan="2">TOTAL DUE</td>
        <td>{{ number_format($total_amount - $payment_types->sum('amount_paid')) }}</td>
     </tr>
  </tfoot>
</table>

     
<div id="details" class="clearfix">
  @if($payment->recheck == 1)
    <div id="client">
      <table>
        <tr>
          <td>Verified by Accounts</td>

        @if($payment->recheck == 0)
          <td><img src="{{ asset('img/tick.png') }}" width="30"></td>
        @elseif($payment->recheck == 1)
          <td><img src="{{ asset('img/cross.png') }}" width="30"></td>
        @endif

        @if(Auth::user()->user_role == 'accountant')
          <td><a href="{{ route('payment.verification', $payment->id) }}" class="btn btn-success">Verify</a></td>
        @endif

        </tr>
      </table>
    </div>
    @endif
</div>

<h2 class="name">Payment Structure(s):</h2>
<div>Total amount received: {{ number_format($payment_types->sum('amount_received')) }}</div>
<hr>

<table class="table table-bordered">
  <thead>
    <tr>
      <th><b>Payment Type</b></th>
      <th><b>Amount Paid</b></th>
      <th><b>Bank Charge</b></th>
      <th><b>Amount Received</b></th>
      <th><b>Action</b></th>
      <th><b>Action</b></th>
      <th><b>Status</b></th>
    </tr>
  </thead>
  <tbody>
    @foreach($payment_types as $payment_type)
    <tr>
      <th>{{ ucfirst($payment_type->payment_type) }}</th>
      <th>{{ number_format($payment_type->amount_paid) }}</th>
      <th>{{ $payment_type->bank_charge }}%</th>
      <th>{{ number_format($payment_type->amount_received, 2) }}</th>
      <th><a  href="{{ route('payment.structure.client', [$payment_type->id, $payment_type->payment_type]) }}" class="btn btn-info btn-sm">View Details</a></th>
      <th>
        <a href="{{ route('payment.client.recheck.payment_type', $payment_type->id) }}" class="btn btn-danger btn-sm">Recheck</a>
      </th>
      <th>
        @if($payment_type->recheck == 0)
        <p>Checked</p>
        @elseif($payment_type->recheck == 1)
        <p>Pending</p>
        @else
        <p></p>
        @endif
      </th>
    </tr>
    @endforeach
  </tbody>
</table>

<hr>


{{-- @if(Auth::user()->user_role == 'admin')
  @if($payment->recheck == -1 || $payment->recheck == 0)
    <a href="{{ route('payment.client.payment.recheck', $payment->id) }}" class="btn btn-danger btn-block" style="width: 100%">Recheck This Entry</a>
  @elseif($payment->recheck == 1)
    <a href="javascript:void(0)" class="btn btn-success btn-block" style="width: 100%">Sent for Recheck</a>
  @endif
@endif --}}

</main>

</body>
</html>