<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Mail;

class TextController extends Controller
{
    public function smsIndex($client_id)
    {
    	$data['active_class'] = 'my-clients';
    	$data['client'] = User::find($client_id);

    	return view('texts.sms_index', $data);
    }

    public function sendSms(Request $request, $client_id)
    {
    	$client = User::find($client_id);
    	$sms_data['phone'] = $client->mobile;
    	$sms_data['message'] = $request->sms;

    	$this->smsSender($sms_data);

    	return redirect()->back();
    }

    public function emailIndex($client_id)
    {
    	$data['active_class'] = 'my-clients';
    	$data['client'] = User::find($client_id);

    	return view('texts.email_index', $data);
    }

    public function smsSender($sms_data)
    {
    	$phone = $sms_data['phone'];
        $message = urlencode($sms_data['message']);

        $url = "http://gicl.powersms.net.bd/httpapi/sendsms?userId=form_sms&password=gicsms123&smsText=$message&commaSeperatedReceiverNumbers=$phone";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");     
        $response = curl_exec($ch);
        curl_close($ch);
    }

    public function sendEmail(Request $request, $client_id)
    {
    	$client = User::find($client_id);
    	$data['email'] = $client->email;
    	$data['subject'] = $request->subject;
    	$data['text'] = $request->email;

    	Mail::send('mail.custom', $data, function ($message) use ($data) {
            $message->from('s.simab@gmail.com', $data['subject']);
            $message->to($data['email']);
            $message->subject($data['subject']);

        });

        return redirect()->back();
    }
}
