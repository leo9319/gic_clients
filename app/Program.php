<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = ['program_name'];

    public function steps()
    {
    	return $this->hasMany('App\Step');
    }
}
