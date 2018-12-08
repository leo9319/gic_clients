<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    protected $guarded = [];

    public function payment()
    {
    	return $this->belongsTo('App\Payment');
    }
}
