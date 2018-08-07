<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['title', 'appointer_id', 'client_id', 'app_date', 'app_time'];

    public function appointer() 
    {
    	return $this->hasMany('App\User', 'id', 'appointer_id');
    }

    public function client() 
    {
    	return $this->hasMany('App\User', 'id', 'client_id');
    }

    public static function getClientsAppointments($client_id)
    {
        return static::where('client_id', $client_id)
            ->orderBy('app_date', 'desc')
            ->orderBy('app_time', 'desc')
            ->get();
    }

    public static function getUsersAppointments($appointer_id)
    {
    	return static::where('appointer_id', $appointer_id)
    		->orderBy('app_date', 'desc')
    		->orderBy('app_time', 'desc')
    		->get();
    }
}
