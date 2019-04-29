<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\RmClient;
use App\CounsellorClient;
use App\Reminder;
use Illuminate\Support\Facades\Mail;

class ReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['active_class'] = 'reminders';
        $data['clients'] = User::userRole('client')->get();
        $user = Auth::user();

        if($user->user_role == 'rm') {

            $client_ids = RmClient::getAssignedClients($user->id)->pluck('client_id');
            

        } elseif(Auth::user()->user_role == 'counselor') {

            $client_ids = CounsellorClient::getAssignedClients($user->id)->pluck('client_id');

        }

        $data['reminders'] = Reminder::whereIn('client_id', $client_ids)->get();


        return view('reminders.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['active_class'] = 'reminders';
        

        $user = Auth::user();

        if($user->user_role == 'rm') {

            $client_ids = RmClient::getAssignedClients($user->id)->pluck('client_id');
            

        } elseif(Auth::user()->user_role == 'counselor') {

            $client_ids = CounsellorClient::getAssignedClients($user->id)->pluck('client_id');

        }

        $data['clients'] = User::userRole('client')->whereIn('id', $client_ids)->get();

        return view('reminders.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'end_date' => 'required',
        ]);
        
        Reminder::create([
            'client_id' => $request->client_id,
            'mobile' => json_encode($request->mobile),
            'email' => json_encode($request->email),
            'message' => $request->message,
            'end_date' => $request->end_date,
        ]);

        $data['text'] = $request->message;
        $number_array = [];

        foreach ($request->email as $email) {

            $data['email'] = $email;

            $this->sendEmail($data);

        }

        foreach ($request->mobile as $phone) {

            array_push($number_array, $phone);

        }

        $number_array = implode(",", $number_array);

        $this->sendSMS($data['text'], $number_array);

        return redirect()->route('reminders.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Reminder $reminder)
    {
        $data['active_class'] = 'reminders';
        $data['reminder'] = $reminder;
        // $data['reminder'] = Reminder::find($);
        $data['clients'] = User::userRole('client')->get();

        return view('reminders.edit', $data);

        // return $reminder;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'client_id' => 'required',
            'mobile' => 'required',
            'email' => 'required',
            'end_date' => 'required',
        ]);
        
        Reminder::find($id)->update([
            'client_id' => $request->client_id,
            'mobile' => json_encode($request->mobile),
            'email' => json_encode($request->email),
            'message' => $request->message,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('reminders.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 
    }

    public function delete(Request $request)
    {
        Reminder::find($request->reminder_id)->delete();

        return back();
    }

    public function getReminderData(Request $request)
    {
        $data = Reminder::find($request->id);

        return response()->json($data);
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
