<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class DepartmentTarget extends Model
{
    protected $guarded = [];

    public static function getCurrentMonthTarget($department)
    {
    	return static::where([
    		'department' => $department,
    		'month'      => Carbon::now()->format('Y-m-01')
    	])->first();
    }

    public function getTargetAchieved($department, $month_and_year, $start_date, $end_date)
    {
        $target_achieved = 0;

        if($month_and_year) {
            $payments = Payment::getMonthyPayment($month_and_year);
        } else {
            $payments = Payment::getPaymentWithDateRange($start_date, $end_date);
        }

        foreach ($payments as $key => $payment) {
            if($payment->totalAmount() == $payment->totalApprovedPayment->sum('amount_paid')) {
                if ($department == 'counseling') {
                    $target_achieved += $payment->stepInfo->targetSetting->counselor_count;
                } else {
                    $target_achieved += $payment->stepInfo->targetSetting->rm_count;
                }
            }
        }

        return $target_achieved;
    	
    }
}
