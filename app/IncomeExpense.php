<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncomeExpense extends Model
{
    protected $guarded = [];

    public function clients()
    {
    	return $this->hasMany('App\User');
    }
}
