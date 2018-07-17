<?php

namespace App\Console\Commands;

use App\ClientTask;
use App\User;
use Carbon\Carbon;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class sendEmailAndSmsReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendEmailAndSmsReminder:sendemailsms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This is for sending email and sms reminder to the Rms and client';

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
    public function handle(){

        $current = Carbon::now();
        $future_date = $current->addDays(3);


        $incomplete_tasks = ClientTask::where([['status','=','incomplete'],['assigned_date','<=',$future_date]])->get();
        $incomplete_task_unique_users = $incomplete_tasks->unique('client_id');
        foreach ($incomplete_task_unique_users as $incomplete_task_unique_user_single){

            $user_client = User::find($incomplete_task_unique_user_single->client_id);
            $user_assignee = User::find($incomplete_task_unique_user_single->assignee_id);


            /*single user data sending task*/
            foreach ($incomplete_tasks as $a_single_task){
                //
                if ($a_single_task->client_id == $incomplete_task_unique_user_single->client_id ){


                    $carbon_assigned_date = Carbon::now();

                    $assigned_date = new \DateTime($a_single_task->assigned_date);
                    /*reminder and deadline over*/
                    $diff = $carbon_assigned_date->diffInDays($assigned_date);
                    /*definign task holders*/
                    $task_of_single_user_deadline = array();
                    $task_of_single_user_reminder = array();

                    if ( $diff<=0){

                        $single_user_task_full_info_deadline = ClientTask::find($a_single_task->task_id);
                        $task_of_single_user_deadline[] = $single_user_task_full_info_deadline->taskName->task_name;
                    }

                    /*reminder for reminder*/
                    if ( $diff>=2  && $diff <=3){

                        $single_user_task_name = ClientTask::find($a_single_task->task_id);
                        $task_of_single_user_reminder[] = $single_user_task_name->taskName->task_name;
                    }





                    //$task_of_single_user[] = $task1->task_id;
                }


            }

            /*get  personal and necessary  data of a client to and array user data for rm and councilor use*/

            $data_of_single_client = [
                'name'              => $user_client->name,
                'email'             => $user_client->email,
                'mobile'            => $user_client->mobile,
                'assignee_role'     => $user_assignee->user_role,
                'assignee_email'    => $user_assignee->email,
                'assignee_mobile'   => $user_assignee->mobile,


            ];

            /*get  personal and necessary  data of a Rm or a councilor to and array also user data for rm and councilor use*/
            $data_of_single_rm_or_councilor= [
                'name'          => $user_assignee->name,
                'email'         => $user_assignee->email,
                'mobile'        => $user_assignee->mobile,
                'client_email'  => $user_client->email,
                'client_mobile' => $user_client->mobile,
                'client_name'   =>$user_client->name,


            ];


            /*sending deadline email and sms*/
            if (sizeof($task_of_single_user_deadline) >0){

                /*for client*/
                $data_of_single_client['subject'] = "Deadline is Over";
                $data_of_single_client['tasks'] = $task_of_single_user_deadline;




                /*for rm and councilor*/
                $data_of_single_rm_or_councilor['subject'] = "Deadline is Over";
                $data_of_single_rm_or_councilor['tasks'] = $task_of_single_user_deadline;


                //send to client by send email function
                $this->sendMaiToClient($data_of_single_client);
                /*send sms*/
                $this->sendMessageToClient($data_of_single_client);


                //send to rm and councilor
                $this->sendMaiToRmAndCouncilor($data_of_single_rm_or_councilor);
                //send sms
                $this->sendMessageToRmAndCouncilor($data_of_single_rm_or_councilor);


            }
            if (sizeof($task_of_single_user_reminder) > 0){
                /*client*/
                $data_of_single_client['subject'] = "Reminder";
                $data_of_single_client['tasks'] = $task_of_single_user_reminder;

                /*rm and councilor*/
                $data_of_single_rm_or_councilor['subject'] = "Reminder";
                $data_of_single_rm_or_councilor['tasks'] = $task_of_single_user_reminder;
                //send to client by send email function
                $this->sendMaiToClient($data_of_single_client);
                /*send sms*/
                $this->sendMessageToClient($data_of_single_client);


                //send to rm and councilor
                $this->sendMaiToRmAndCouncilor($data_of_single_rm_or_councilor);
                //send sms
                $this->sendMessageToRmAndCouncilor($data_of_single_rm_or_councilor);


            }



        }

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



    }
}
