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
    	$target = Target::find(3);

        return $target->getIndividualTargetAchieved($target->user_id, $target->month_year, $target->start_date, $target->end_date);




        // $orders = DB::table('orders')
        //         ->select('department', DB::raw('SUM(price) as total_sales'))
        //         ->groupBy('department')
        //         ->havingRaw('SUM(price) > ?', [2500])
        //         ->get();

    }

}
