<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Target extends Model
{
    protected $fillable = ['user_id', 'target', 'achieved', 'month_year'];

    public function userInfo()
    {
    	return $this->hasMany('App\User', 'id', 'user_id');
    }

    // public static function userCurrentMonthTarget($user_id)
    // {
    //     return static::where('user_id', $user_id)->whereDate('month_year', Carbon::parse()->format('Y-m-01'))->get();
    // }

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
}
