<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RmClient extends Model
{
    public static function getAssignedRms($client_id) 
    {
    	return RmClient::where('client_id', '=', $client_id)->get();
    }
}
