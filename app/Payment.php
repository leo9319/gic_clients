<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    // protected $fillable = ['client_id', 'program_id', 'step_id', 'payment_type', 'card_type', 'name_on_card', 'card_number', 'expiry_date', 'approval_code', 'bank_name', 'cheque_number', 'phone_number', 'opening_fee', 'embassy_student_fee', 'service_solicitor_fee', 'other', 'total_amount', 'total_after_charge', 'bank_charges', 'amount_paid', 'verified', 'cheque_verified', 'recheck', 'description', 'dues', 'due_date', 'created_by', 'created_at'];

    protected $guarded = [];

    public function userInfo()
    {
    	return $this->hasOne('App\User', 'id', 'client_id');
    }

    public function programInfo()
    {
    	return $this->hasOne('App\Program', 'id', 'program_id');
    }

    public function stepInfo()
    {
        return $this->hasOne('App\Step', 'id', 'step_id');
    }
    
    public function totalPayment()
    {
    	return $this->hasMany('App\PaymentType');
    }

    public function totalVerifiedPayment()
    {
        return $this->hasMany('App\PaymentType')
                    ->where('cheque_verified', '!=', 0)
                    ->where('online_verified', '!=', 0)
                    ->where('refund_payment', '!=', 1);
                    
    }

    public function totalAmount()
    {
        return $this->opening_fee + $this->embassy_student_fee + $this->service_solicitor_fee + $this->other;
    }
    

}
