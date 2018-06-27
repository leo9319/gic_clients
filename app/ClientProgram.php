<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProgram extends Model
{
    public static function programs($client_id)
    {
    	return ClientProgram::where('client_id', $client_id)->get();
    }

    public function programInfo()
    {
    	return $this->hasMany('App\Program', 'id', 'program_id');
    }
}
