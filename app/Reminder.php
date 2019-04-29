<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $guarded = [];

    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'client_id');
    }
}
