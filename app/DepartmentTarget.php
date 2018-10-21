<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class DepartmentTarget extends Model
{
    protected $fillable = ['department', 'month', 'target'];

    public static function getCurrentMonthTarget($department)
    {
    	return static::where([
    		'department' => $department,
    		'month' => Carbon::now()->format('Y-m-01')
    	])->first();
    }
}
