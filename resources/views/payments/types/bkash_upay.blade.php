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

<h2 class="name">Mobile Payment Information:</h2>
<hr>

<table align="left">
  <tbody>
    <tr>
      <td>Date:</td>
      <td>{{ Carbon\Carbon::parse($payment_info->created_at)->format('jS M, Y') }}</td>
      <td></td>
    </tr>
    <tr>
      <td>Deposited In:</td>
      <td>{{ strtoupper($payment_info->bank_name) }}</td>
      <td></td>
    </tr> 
    <tr>
      <td>Mobile Number:</td>
      <td>{{ $payment_info->phone_number }}</td>
      <td></td>
    </tr>
    <tr>
      <td>Bank Charges:</td>
      <td>{{ $payment_info->bank_charge }}%</td>
      <td></td>
    </tr>

    @if($payment_info->payment_type == 'bkash_salman')

    <tr>
      <td>Approved?</td>
      <td>{{ ($payment_info->bkash_salman_verified == 1) ? 'Yes' : 'No'}}</td>
      <td></td>
    </tr>

    @endif

    @if($payment_info->payment_type == 'bkash_corporate')

    <tr>
      <td>Approved?</td>
      <td>{{ ($payment_info->bkash_corporate_verified == 1) ? 'Yes' : 'No'}}</td>
      <td></td>
    </tr>

    @endif

  </tbody>
</table>


</main>

</body>
</html>