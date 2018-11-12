<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['client_id', 'program_id', 'step_id', 'payment_type', 'card_type', 'name_on_card', 'card_number', 'expiry_date', 'approval_code', 'bank_name', 'cheque_number', 'phone_number', 'opening_fee', 'embassy_student_fee', 'service_solicitor_fee', 'other', 'total_amount', 'total_after_charge', 'bank_charges', 'amount_paid', 'verified', 'cheque_verified', 'recheck', 'description', 'created_by', 'created_at'];

    public function userInfo()
    {
    	return $this->hasMany('App\User', 'id', 'client_id');
    }

    public function programInfo()
    {
    	return $this->hasMany('App\Program', 'id', 'program_id');
    }
}
