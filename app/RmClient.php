<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use User;

class RmClient extends Model
{
    public static function getAssignedRms($client_id) 
    {
    	return RmClient::where('client_id', '=', $client_id)->get();
    }

    public function profiles() 
    {
    	return $this->hasMany('App\User', 'id', 'client_id');
    }

    public static function clients($rm_id)
    {
    	return RmClient::where('rm_id', $id)->get();
    }
}
