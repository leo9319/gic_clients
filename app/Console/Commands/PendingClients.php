<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Reminder;
use Carbon\Carbon;

class PendingClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pending:clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sending Email and SMS to pending clients';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // get all the clients that are scheduled to be sent an email and an sms today and tomorrow:

        
        $from = Carbon::today()->toDateString();
        $to = Carbon::tomorrow()->toDateString();

        $all_entries = Reminder::whereBetween('end_date', [$from, $to])->where('status', 1)->get();

        foreach ($all_entries as $entry) {

            $data['text'] = $entry->message;
            $number_array = [];

            $emails = json_decode($entry->email);

            foreach ($emails as $email) {

                $data['email'] = $email;

                $this->sendEmail($data);

            }

            $phones = json_decode($entry->mobile);

            foreach ($phones as $phone) {

                array_push($number_array, $phone);

            }

            $number_array = implode(",", $number_array);

            $this->sendSMS($data['text'], $number_array);

        }
        
    }

    public function sendEmail($data) 
    {
        Mail::send('mail.reminder', $data, function($message) use ($data) {
            $message->from('gic.crm.123@gmail.com', 'GIC');
            $message->to($data['email']);
            $message->subject('Payment Reminder');
        });
    }

    public function sendSMS($message, $numbers) 
    {
        $message = urlencode($message);

        $url = "http://gicl.powersms.net.bd/httpapi/sendsms?userId=form_sms&password=gicsms123&smsText=$message&commaSeperatedReceiverNumbers=$numbers";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt($ch, CURLOPT_ENCODING, "gzip,deflate");
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
