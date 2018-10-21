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

         <div>

            <a href="{{ url('http://gicbd.com') }}">http://gicbd.com</a>

         </div>

   </div>

   </header>

   <main>

      <div id="details" class="clearfix">


         <div id="client">

            <div class="to">INVOICE TO:</div>

            <h2 class="name">

               {{ $client ? $client->name : 'Client Not Found!' }} 

            </h2>

            <b>{{ $client ? $client->client_code : 'Client Code Not Found!' }}</b>

            <div class="address">

               {{ $client_file_info ? $client_file_info->address : 'Address Not Found!' }}

            </div>

            <div class="email">

               {{ $client ? $client->email : 'Email Not Found!' }}

            </div>

         </div>


        <div id="invoice">

            <h1>
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

            </h1>

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
                 <h3>Opening Fee</h3>
              </td>
              <td></td>
              <td></td>
              <td class="unit">{{ number_format($payment->opening_fee) }}</td>

           </tr>
           <tr>
              <td class="no">02</td>
              <td class="desc">
                 <h3>Embassy Student Fee</h3>
              </td>
              <td></td>
              <td></td>
              <td class="unit">{{ number_format($payment->embassy_student_fee) }}</td>

           </tr>
           <tr>
              <td class="no">03</td>
              <td class="desc">
                 <h3>Service Solicitor Fee</h3>
              </td>
              <td></td>
              <td></td>
              <td class="unit">{{ number_format($payment->service_solicitor_fee) }}</td>

           </tr>
           <tr>
              <td class="no">04</td>
              <td class="desc">
                 <h3>Other</h3>
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
              <td>{{ number_format($payment->total_amount) }}</td>
           </tr>
           <tr>
              <td colspan="2"></td>
              <td colspan="2">AMOUNT PAID</td>
              <td>{{ number_format($payment->amount_paid) }}</td>
           </tr>
           <tr>
              <td colspan="2"></td>
              <td colspan="2">TOTAL DUE</td>
              <td>{{ number_format($payment->total_amount - $payment->amount_paid) }}</td>
           </tr>
        </tfoot>
     </table>

     
      <div id="details" class="clearfix">

         <div id="client">
            <table>
               <tr>
                  <td>Verified by Accounts</td>
                  @if($payment->verified == 1)
                  <td><img src="{{ asset('img/tick.png') }}" width="30"></td>
                  @else
                  <td><img src="{{ asset('img/cross.png') }}" width="30"></td>
                  @endif
                  @if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'accountant')
                  <td><a href="{{ route('payment.verification', $payment->id) }}" class="btn btn-success">Verify</a></td>
                  @endif
               </tr>
               @if($payment->payment_type == 'cheque')
               <tr>
                  <td>Cheque verified</td>
                  @if($payment->cheque_verified == 1)
                  <td><img src="{{ asset('img/tick.png') }}" width="30"></td>
                  @else
                  <td><img src="{{ asset('img/cross.png') }}" width="30"></td>
                  @endif
                  @if(Auth::user()->user_role == 'admin' || Auth::user()->user_role == 'counselor'|| Auth::user()->user_role == 'rm')
                  <td><a href="{{ route('payment.cheque.verification', $payment->id) }}" class="btn btn-success">Verify</a></td>
                  @else
                  <td></td>
                  @endif
               </tr>
               @endif
            </table>
         </div>

         <div id="invoice">

            <h2 class="name">Payment Method: <b>{{ ucfirst($payment->payment_type) }}</b></h2>

            <div class="date">

               Invoice Created: {{ Carbon\Carbon::parse($payment->created_at)->format('d-m-Y') }}

            </div>

            <div class="date">

              @if(isset($payment->due_clearance_date))
                <p style="color: red"><b>Due Clearance Date: {{  Carbon\Carbon::parse($payment->due_clearance_date)->format('d-m-Y') }}</b></p>
              @else 
                <p></p>
              @endif

               

            </div>

            <hr>

            @if($payment->payment_type == 'card' || $payment->payment_type == 'emi')

            <table>
               <tr>
                  <td><b>Card Type</b>
                  <td>{{ strtoupper($payment->card_type) }}</td>
               </tr>
               <tr>
                  <td><b>Bank Name</b>
                  <td>{{ strtoupper($payment->bank_name) }}</td>
               </tr>
               <tr>
                  <td><b>Name on card:</b>
                  <td>{{ $payment->name_on_card }}</td>
               </tr>
               <tr>
                  <td><b>Card number:</b>
                  <td>{{ $payment->card_number }}</td>
               </tr>
               <tr>
                  <td><b>Expiry Date:</b>
                  <td>{{ $payment->expiry_date }}</td>
               </tr>
            </table>

            @elseif($payment->payment_type == 'cheque')
               <table>
               <tr>
                  <td><b>Bank Name</b>
                  <td>{{ $payment->bank_name }}</td>
               </tr>
               <tr>
                  <td><b>Cheque Number:</b>
                  <td>{{ $payment->cheque_number }}</td>
               </tr>
            </table>

            @elseif($payment->payment_type == 'online')
            <table>
               <tr>
                  <td><b>Bank Name</b>
                  <td>{{ strtoupper($payment->bank_name) }}</td>
               </tr>
            </table>

            @elseif($payment->payment_type == 'bkash' || $payment->payment_type == 'upay')
            <table>
               <tr>
                  <td><b>Phone Number</b>
                  <td>{{ $payment->phone_number }}</td>
               </tr>
            </table>

            @endif


            {{-- @if(Auth::user()->user_role == 'accountant')

               @if($payment->verified == 0)

                  <a href="{{ route('payment.verification', $payment->id) }}" class="btn btn-success">Verify Payment</a>

               @else

                  <h2 style="color: green"><b>Verified <i class="fas fa-check"></i></b></h2>

               @endif

            @elseif(Auth::user()->user_role == 'counselor' || Auth::user()->user_role == 'rm')


               @if($payment->verified == 1)

                  <h2 style="color: green"><b>Verified By Accounts <i class="fas fa-check"></i></b></h2>

                     @if($payment->cheque_verified == 0 && $payment->payment_type == 'cheque')

                        <a href="{{ route('payment.cheque.verification', $payment->id) }}" class="btn btn-success">Verify Cheque</a>

                     @elseif($payment->cheque_verified == 1 && $payment->payment_type == 'cheque')

                        <h2 style="color: green"><b>Cheque Verified <i class="fas fa-check"></i></b></h2>

                     @endif

               @else

                  <h2 style="color: red"><b>Unverified <i class="fas fa-times"></i></b></h2>

               @endif

            @endif

            @if(Auth::user()->user_role == 'admin')

               @if($payment->verified == 1)

                  <h2 style="color: green"><b>Verified By Accounts <i class="fas fa-check"></i></b></h2>

               @endif

               @if($payment->cheque_verified == 1 && $payment->payment_type == 'cheque')

                  <h2 style="color: green"><b>Cheque Verified By Counselor / RM <i class="fas fa-check"></i></b></h2>

               @endif

            @endif --}}

         </div>

      </div>

   </main>

</body>
</html>