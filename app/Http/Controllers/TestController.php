<?php

namespace App\Http\Controllers;
use App\ClientTask;
use Faker\Provider\cs_CZ\DateTime;
use App\User;
use App\Reminder;
use App\Target;
use App\CounsellorClient;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use URL;
use Artisan;
use DB;

class TestController extends Controller
{
    public function test()
    {
        $data['active_class'] = 'payments';
    	return view('test', $data);

    }

}
