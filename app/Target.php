<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon;

class Target extends Model
{
    protected $fillable = ['user_id', 'target', 'month_year'];

    public function userInfo()
    {
    	return $this->hasMany('App\User', 'id', 'user_id');
    }

    public static function currentMonthTarget()
    {
    	return static::whereDate('month_year', Carbon\Carbon::parse()->format('Y-m-01'))->get();
    }
}
