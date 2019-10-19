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

    public function getTargetAchieved()
    {
        $target_achieved = 0;

        if ($this->steps == 'all' || $this->steps == NULL) {
            $payments = new Payment;
        } else {
            $payments = Payment::withSpecificSteps($this->steps);
        }

        if($this->month) {
            $payments = $payments->whereMonth('created_at', Carbon::parse($this->month)->month)
                                 ->whereYear('created_at', Carbon::parse($this->month)->year)
                                 ->where('location', $this->location)
                                 ->get();
        } else {
            $payments = $payments->whereBetween('created_at', [$this->start_date, $this->end_date])->get();

        }

        foreach ($payments as $key => $payment) {
            if($payment->totalAmount() == $payment->totalApprovedPayment->sum('amount_paid')) {
                if ($this->department == 'counseling') {
                    $target_achieved += $payment->stepInfo->targetSetting->counselor_count;
                } else {
                    $target_achieved += $payment->stepInfo->targetSetting->rm_count;
                }
            }
        }

        return $target_achieved;
    	
    }
}
