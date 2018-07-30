<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientFileInfo extends Model
{
    protected $fillable = ['client_id', 'creator_id', 'address', 'country_of_choice', 'amount_paid'];

    public static function moreInfo($client_id)
    {
    	return static::where('client_id', $client_id)->first();
    }
}
