<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['client_id', 'program_id', 'step_no', 'opening_fee', 'embassy_student_fee', 'service_solicitor_fee', 'other', 'total_amount', 'amount_paid'];

    public function userInfo()
    {
    	return $this->hasMany('App\User', 'id', 'client_id');
    }

    public function programInfo()
    {
    	return $this->hasMany('App\Program', 'id', 'program_id');
    }
}
