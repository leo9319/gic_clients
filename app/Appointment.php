<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['appointer_id', 'client_id', 'app_date', 'app_time'];

    public function appointer() 
    {
    	return $this->hasMany('App\User', 'id', 'appointer_id');
    }

    public function client() 
    {
    	return $this->hasMany('App\User', 'id', 'client_id');
    }
}
