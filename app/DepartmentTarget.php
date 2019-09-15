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

    public function getTargetAchieved($department, $month_year, $start_date, $end_date)
    {
    	$target_achieved = 0;

    	if($month_year) {

    		$steps = Payment::whereMonth('created_at', Carbon::parse($month_year)->month)
    				 ->whereYear('created_at', Carbon::parse($month_year)->year)->get();

		    foreach ($steps as $key => $step) {

		    	if ($department == 'counseling') {
		    		$target_achieved += $step->stepInfo->target->counselor_count;
		    	} else {
		    		$target_achieved += $step->stepInfo->target->rm_count;
		    	}
		    	
		    }

			return $target_achieved;

    	} else {

    		return 'N/A';

    	}
    	
    }
}
