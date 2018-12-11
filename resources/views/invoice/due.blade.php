<!DOCTYPE html>
<html>
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'>
    <title>Customer Invoice</title>

    <style>
        #page-wrap{
            padding: 1em;
        }

        #address{
            line-height: 1.9em;

        }
        #address .font{
            font-family: "Lucida Grande", "Lucida Sans Unicode", Verdana, Arial, Helvetica, sans-serif;
            line-height: 1.6em;
            color: grey;
        }

        #header{

            background-color: #222222;
            color: whitesmoke;
            text-align: center;
            letter-spacing: .6em;
            padding: .6em;
            margin-bottom: 1.5em;
        }
        .bold-later{

            font-weight: bold

        }
        #company-name{
            font-size: 1.5em;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
        }
        #identity{
            float: left;
        }
        #logo{
            float: right;
        }
        .clear{
            clear: both;
        }
        .table-personal-info .tr{
            margin-top: 2em;
            font-weight: bold;
            line-height: 1.3em;
        }
        .table-personal-info{
            margin-top: 1.5em;
        }
        #details th{
            background-color: #EEEEEE;
        }
       #details{

            text-align: center;
           border-collapse: collapse;
           margin-top: 2em;


        }
        #details td,tr{
            padding: .5em;
        }
        #terms{
            margin-top: 2em;
            text-align: center;


        }


        #meta{
            border-collapse: collapse;



        }
        #meta td{
            padding: .2em;
        }
        #meta .back{
            background-color: #EEEEEE;

        }

    </style>


</head>
<body>
<div id="page-wrap">
    <div id="header">INVOICE</div>

    <div>



        <div id="identity">
            <div id="address">
                <div id="company-name" class="bold-later">Global Immigration <br>Consultants Ltd (GIC)</div>

                    <div class="font">Gulshan 1</div>
                    <div  class="font">1st Floor, Plot 56/b, Road No 132</div>
                    <div  class="font">Dhaka-1212, Bangladesh</div>

            </div>



                <div >
                    <table class="table-personal-info">
                        <tr>
                            <td class="tr">Full Name:</td>
                            <td>{{ $name }}</td>
                        </tr>
                        <tr>
                            <td class="tr">Present Address:</td>
                            <td>{{ $address }}</td>
                        </tr>
                        <tr>
                            <td class="tr">Phone Number:</td>
                            <td>{{ $mobile }}</td>
                        </tr>
                        <tr>
                            <td class="tr">Email Address:</td>
                            <td>{{ $email }}</td>
                        </tr>
                    </table>
                </div>

        </div>



        <div id="logo">

                <div id="" >
                    <img style="margin-left: 4em" src="http://gicbd.com/wp-content/uploads/2017/04/logo.png" alt="logo" height="70" width="190"/>
                </div>
                <table id="meta" >
                    <tr>
                        <td class="back">Receipt ID</td>
                        <td>{{ $payment->receipt_id }}D</td>
                    </tr>
                    <tr>
                        <td class="back">Due Cleared On</td>
                        <td>{{ Carbon\Carbon::parse($payments->first()->payment->due_cleared_date)->format('d-M-y') }}</td>
                    </tr>
                    <tr>
                        <td class="back">Client Code</td>
                        <td>{{$client_code}}</td>
                    </tr>
                    <tr>
                        <td class="back">Invoice created by</td>
                        <td>{{ $created_by }}</td>
                    </tr>
                </table>
            </div>

    </div>
    <div class="clear">

    </div>

    <div >
        <table  width="100%" id="details" border="1">
            <tr>
                <th>Programs</th>
                <th>Step Name</th>
                <th>Previously Paid</th>
                <th>Due Paid</th>
                <th>Total Amount Paid</th>
            </tr>
            <tr id="hiderow">
                <td>{{ $program }}</td>
                <td>{{ $step->step_name }}</td>
                <td>{{ number_format($payments->where('due_payment', 0)->sum('amount_paid')) }}</td>
                <td>{{ number_format($payments->where('due_payment', 1)->sum('amount_paid')) }}</td>
                <td>{{ number_format($payments->sum('amount_paid')) }}</td>
            </tr>
        </table>

        <h3>Payment Methods:</h3>

        <table width="100%" id="details" border="1">
            <tr>
                <th>Method</th>
                <th>Amount Paid</th>
            </tr>
            @foreach($payments as $payment)
            <tr id="hiderow">
                <td>{{ ucfirst($payment->payment_type) }}</td>
                <td>{{ number_format($payment->amount_paid) }}</td>
            </tr>
            @endforeach
        </table>

        <div>
            <h4>Comments:</h4>
            <p>{{ $comments }}</p>
        </div>
        
        <div id="terms">
            <small>*This is a computer generated invoice*</small>
            <div>If you have any questions concerning this invoice, contact us @ 09678744223.</div>
        </div>
    </div>

</div>

</body>
</html>
