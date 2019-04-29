<?php

namespace App\Http\Controllers;
use App\ClientTask;
use Faker\Provider\cs_CZ\DateTime;
use App\User;
use App\Reminder;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use URL;
use Artisan;

class TestController extends Controller
{
    //
    public function index()
    {
        $data = ['id' => 12];

        Mail::send('mail.recheck',$data,function ($message) use ($data){
            $message->from('gic.crm.123@gmail.com', 'GIC');
            $message->to('leo_9319@yahoo.com');
            $message->subject('Test');

        });
    }

    public function sendMaiToClient($data_of_single_client){
        Mail::send('mail.client',$data_of_single_client,function ($message) use ($data_of_single_client){
            $message->from('s.simab@gmail.com', 'GIC');
            $message->to($data_of_single_client['email']);
            $message->subject($data_of_single_client['subject']);

        });
    }
    public function sendMaiToRmAndCouncilor($data_of_single_rm_or_councilor){
        Mail::send('mail.rmandcouncilor',$data_of_single_rm_or_councilor,function ($message) use ($data_of_single_rm_or_councilor){
            $message->from('s.simab@gmail.com', 'GIC');
            $message->to($data_of_single_rm_or_councilor['email']);
            $message->subject($data_of_single_rm_or_councilor['subject']);

        });
    }

    public function sendMessageToClient($data_of_single_client){



        $tasks = $data_of_single_client['tasks'];
        $tasks_string = "";
        foreach ($tasks as $key=>$task){
            $key = $key+1;
            $tasks_string = $tasks_string .$key.".".$task."\n";
        }


        $phone = $data_of_single_client['mobile'];

        $message ="Dear {$data_of_single_client['name']},\nThe tasks {$data_of_single_client['subject']}:\n$tasks_string\nPlease contact your {$data_of_single_client['assignee_role']}\n Phone:{$data_of_single_client['assignee_mobile']}\nEmail:{$data_of_single_client['assignee_email']}\nGIC.";

        $message = urlencode($message);

        $url = "http://gicl.powersms.net.bd/httpapi/sendsms?userId=form_sms&password=gicsms123&smsText=$message&commaSeperatedReceiverNumbers=$phone";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
        $response = curl_exec($ch);
        curl_close($ch);
        echo $phone . ': ';
        $response = str_replace('Success Count : 1 and Fail Count : 0', 'Sent Successfully!', $response);
        $response = str_replace('Success Count : 0 and Fail Count : 1', 'Failed!', $response);
        echo $response . '<br>';


    }



    public function sendMessageToRmAndCouncilor($data_of_single_rm_or_councilor){



        $tasks = $data_of_single_rm_or_councilor['tasks'];
        $tasks_string = "";
        foreach ($tasks as $key=>$task){
            $key = $key+1;
            $tasks_string = $tasks_string .$key.".".$task."\n";
        }


        $phone = $data_of_single_rm_or_councilor['mobile'];

        $message ="Dear {$data_of_single_rm_or_councilor['name']},\nThe tasks {$data_of_single_rm_or_councilor['subject']}:\n$tasks_string\nPlease contact your client\n Phone:{$data_of_single_rm_or_councilor['client_mobile']}\nEmail:{$data_of_single_rm_or_councilor['client_email']}\nGIC.";

        $message = urlencode($message);

        $url = "http://gicl.powersms.net.bd/httpapi/sendsms?userId=form_sms&password=gicsms123&smsText=$message&commaSeperatedReceiverNumbers=$phone";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
        $response = curl_exec($ch);
        curl_close($ch);
        echo $phone . ': ';
        $response = str_replace('Success Count : 1 and Fail Count : 0', 'Sent Successfully!', $response);
        $response = str_replace('Success Count : 0 and Fail Count : 1', 'Failed!', $response);
        echo $response . '<br>';


    }

    public function test()
    {
        
    }


}
