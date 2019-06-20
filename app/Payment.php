<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'client_id');
    }

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
    	return $this->hasMany('App\PaymentType')->latest('created_at');
    }

    public function totalVerifiedPayment()
    {
        return $this->hasMany('App\PaymentType')
                    ->where('cheque_verified', '!=', 0)
                    ->where('online_verified', '!=', 0)
                    ->where('bkash_salman_verified', '!=', 0)
                    ->where('bkash_corporate_verified', '!=', 0)
                    ->where('refund_payment', '!=', 1);
                    
    }

    public function totalApprovedPayment()
    {
        return $this->hasMany('App\PaymentType')
                    ->where('cheque_verified', 1)
                    ->where('online_verified', 1)
                    ->where('bkash_salman_verified', 1)
                    ->where('bkash_corporate_verified', 1)
                    ->where('refund_payment', '!=', 1);
                    
    }

    public function totalAmount()
    {
        $total_amount = $this->opening_fee + 
                        $this->embassy_student_fee + 
                        $this->service_solicitor_fee + 
                        $this->other;

        return $total_amount;
    }
    

}
