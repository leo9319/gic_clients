<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Target extends Model
{
    protected $fillable = ['user_id', 'target', 'achieved', 'month_year', 'start_date', 'end_date'];

    public function userInfo()
    {
    	return $this->hasMany('App\User', 'id', 'user_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public static function getUserTarget($user_id)
    {
        return static::where('user_id', $user_id);
    }

    public static function currentMonthTarget()
    {
    	return static::whereDate('month_year', Carbon::parse()->format('Y-m-01'))->get();
    }

    public static function addOneToTarget($user_id)
    {
    	$target_info = static::where([
            'user_id' => $user_id,
            'month_year' => Carbon::now()->format('Y-m-01')
        ])->first();

        if($target_info) {
            static::updateOrCreate([
                'user_id' => $user_id, 
                'month_year' => Carbon::now()->format('Y-m-01')],
                ['achieved' => ++$target_info->achieved]
            );
        } else {
            static::updateOrCreate([
                'user_id' => $user_id, 
                'month_year' => Carbon::now()->format('Y-m-01')],
                ['achieved' => 1]
            );
        }
    }

    public function getIndividualTargetAchieved($user_id, $month_and_year, $start_date, $end_date)
    {
        $user            = User::find($user_id);
        $target_achieved = 0;
        $rms             = [];

        if($month_and_year) {
            $payments = Payment::getMonthyPayment($month_and_year);
        } else {
            $payments = Payment::getPaymentWithDateRange($start_date, $end_date);
        }

        foreach ($payments as $key => $payment) {
            if($payment->totalAmount() == $payment->totalApprovedPayment->sum('amount_paid')) {
                if ($user->user_role == 'rm') {
                    if (in_array($user_id, $payment->user->getAssignedRms->pluck('rm_id')->toArray())) {
                        $target_achieved += $payment->stepInfo->targetSetting->rm_count;
                    }
                } else {
                    if (in_array($user_id, $payment->user->getAssignedCounselors->pluck('counsellor_id')->toArray())) {
                        $target_achieved += $payment->stepInfo->targetSetting->counselor_count;
                    }
                }
            }
        }

        return $target_achieved;

    }
}
