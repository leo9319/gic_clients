<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Redirect;
use Illuminate\Http\Request;
use Mail;
use App\User;
use App\Appointment;

class gCalendarController extends Controller
{
    protected $client;

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig('client_secret.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);

        $guzzleClient = new \GuzzleHttp\Client(array('curl' => array(CURLOPT_SSL_VERIFYPEER => false)));
        $client->setHttpClient($guzzleClient);
        $this->client = $client;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $calendarId = 'primary';

            $results = $service->events->listEvents($calendarId);
            return $results->getItems();

        } else {
            return redirect()->route('oauthCallback');
        }

    }

    public function oauth()
    {
        session_start();

        $rurl = action('gCalendarController@oauth');
        $this->client->setRedirectUri($rurl);
        if (!isset($_GET['code'])) {
            $auth_url = $this->client->createAuthUrl();
            $filtered_url = filter_var($auth_url, FILTER_SANITIZE_URL);
            return redirect($filtered_url);
        } else {
            $this->client->authenticate($_GET['code']);
            $_SESSION['access_token'] = $this->client->getAccessToken();
            // return redirect()->route('oauthCallback');
            // return redirect()->route('gcalendar.index');
            // return redirect('https://calendar.google.com/calendar/r/eventedit');
            // return Redirect::to('https://calendar.google.com/calendar/r/month?sf=true');
            // return redirect()->url('https://calendar.google.com/calendar/r/month?sf=true');

            return redirect()->route('client.index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('calendar.createEvent');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        session_start();
        $appointee_id = $request->appointee;
        $appointee = User::find($appointee_id);
        $client = User::where('email', $request->client_email)->first();
        $startDateTime = $request->start_date. 'T' .$request->starttime . ':00+06:00';
        $endtime = $dt = Carbon::parse($request->start_date . ' ' .$request->starttime. ':00')->addMinutes(30);

        $endDateTime = $request->start_date. 'T' .$endtime->toTimeString(). '+06:00';

        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);
            $sendNotifications = array('sendNotifications' => true);

            $calendarId = 'primary';
            $event = new Google_Service_Calendar_Event([
                'summary' => $request->title,
                'description' => $request->description,
                'start' => ['dateTime' => $startDateTime],
                // 'sendNotifications' => true,
                'overrides' => array(
                  array('method' => 'email', 'minutes' => 24 * 60),
                  array('method' => 'popup', 'minutes' => 1),
                ),
                'end' => ['dateTime' => $endDateTime],
                'reminders' => ['useDefault' => true],
                'attendees' => [
                                    [
                                        'email' => $request->client_email, 
                                        'responseStatus' => 'needsAction'
                                    ],
                                    [
                                        'email' => $appointee->email,
                                        'responseStatus' => 'needsAction'
                                    ],
                                ],
                "extendedProperties" =>
                ["private" => ["everyoneDeclinedDismissed" => -1],
                  "useDefault" => true]
                
            ]);

            $results = $service->events->insert($calendarId, $event, $sendNotifications);
            if (!$results) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            // return response()->json(['status' => 'success', 'message' => 'Event Created']);
            Appointment::updateOrCreate(
            [
                'appointer_id' => $appointee->id,
                'client_id' => $client->id,
            ],
            [
                'appointer_id' => $appointee->id,
                'client_id' => $client->id,
                'app_date' => $request->start_date,
                'app_time' => $request->starttime,
            ]);

        $appointment = Appointment::where([
                'appointer_id' => $appointee->id,
                'client_id' => $client->id,
                'app_date' => $request->start_date,
                'app_time' => $request->starttime,
        ])->first();

            return redirect()->route('email', [$appointee->id, $client->id, $appointment->id]);
        } else {
            return redirect()->route('oauthCallback');
        }

        // return redirect()->route('email', [$rm->id, $client->id]);

    }

    /**
     * Display the specified resource.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function show($eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);

            $service = new Google_Service_Calendar($this->client);
            $event = $service->events->get('primary', $eventId);

            if (!$event) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return response()->json(['status' => 'success', 'data' => $event]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function update(Request $request, $eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $startDateTime = Carbon::parse($request->start_date)->toRfc3339String();

            $eventDuration = 30; //minutes

            if ($request->has('end_date')) {
                $endDateTime = Carbon::parse($request->end_date)->toRfc3339String();

            } else {
                $endDateTime = Carbon::parse($request->start_date)->addMinutes($eventDuration)->toRfc3339String();
            }

            // retrieve the event from the API.
            $event = $service->events->get('primary', $eventId);

            $event->setSummary($request->title);

            $event->setDescription($request->description);

            //start time
            $start = new Google_Service_Calendar_EventDateTime();
            $start->setDateTime($startDateTime);
            $event->setStart($start);

            //end time
            $end = new Google_Service_Calendar_EventDateTime();
            $end->setDateTime($endDateTime);
            $event->setEnd($end);

            $updatedEvent = $service->events->update('primary', $event->getId(), $event);


            if (!$updatedEvent) {
                return response()->json(['status' => 'error', 'message' => 'Something went wrong']);
            }
            return response()->json(['status' => 'success', 'data' => $updatedEvent]);

        } else {
            return redirect()->route('oauthCallback');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $eventId
     * @return \Illuminate\Http\Response
     * @internal param int $id
     */
    public function destroy($eventId)
    {
        session_start();
        if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {
            $this->client->setAccessToken($_SESSION['access_token']);
            $service = new Google_Service_Calendar($this->client);

            $service->events->delete('primary', $eventId);

        } else {
            return redirect()->route('oauthCallback');
        }
    }

    public function setAppointment($client_id)
    {
        $data['active_class'] = 'clients';
        $data['user'] = User::find($client_id);
        $data['rms'] = User::where('user_role', 'rm')->get();

        return view('appointment.set', $data);
    }

    public function sendEmail($rm_id, $client_id, $appointment_id)
    {
        $rm = User::find($rm_id);
        $client = User::find($client_id);
        $appointment = Appointment::find($appointment_id);

        $date = Carbon::parse($appointment->date)->format('d F, Y');
        $time = Carbon::parse($appointment->app_time)->format('g:i A');
        $message = "";

        $data = [
            'client' => $client,
            'rm' => $rm,
            'date' => $date,
            'time' => $time,
            'email' => $client->email,
            'subject' => 'Appointment with RM'
        ];

        Mail::send('mail.mail', $data, function($message) use ($data) {
            $message->from('s.simab@gmail.com', 'GIC Appointment');
            $message->to($data['email']);
            $message->subject($data['subject']);
        });

        return redirect()->route('sms', [$rm->id, $client->id, $appointment->id]);

    }

    public function sendSMS($rm_id, $client_id, $appointment_id)
    {
        $client = User::find($client_id);
        $rm = User::find($rm_id);
        $appointment = Appointment::find($appointment_id);

        $date = Carbon::parse($appointment->date)->format('d F, Y');
        $time = Carbon::parse($appointment->app_time)->format('g:i A');

        $phone = $client->mobile;
        $username = 'admin';
        $password = 'Generic!1234';
        $message ="Dear $client->name,\nYour appointment has been set on $date at $time with $rm->name. Please be at GIC at least 15 minutes before the aforementioned time.\nThank you.";

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

        return redirect()->back();
        
    }
}
