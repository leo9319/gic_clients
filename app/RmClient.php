<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use User;
use Carbon\Carbon;

class RmClient extends Model
{
    protected $fillable = ['client_id', 'rm_id'];

    public function users()
    {
        return $this->hasMany('App\User', 'id', 'rm_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'rm_id');
    }

    public static function getAssignedRms($client_id) 
    {
    	return RmClient::where('client_id', '=', $client_id)->get();
    }

    public function clients() 
    {
        return $this->hasMany('App\User', 'id', 'client_id');
    }

    // public static function clients($rm_id)
    // {
    // 	return RmClient::where('rm_id', $rm_id)->get();
    // }

    public static function fileOpenedThisMonth($rm_id)
    {
        return static::where('rm_id', $rm_id)->whereMonth('created_at', '=', Carbon::now()->month)->get();;
    }

    public static function totalFilesOpened($rm_id)
    {
        return static::where('rm_id', $rm_id)->get();
    }

    public static function assignRmToClient($all_rms, $client_id) 
    {
        foreach ($all_rms as $rm) {
            static::create([
                'client_id' => $client_id,
                'rm_id' => $rm
            ]);
        }
    }

    public static function assignedRm($client_id)
    {
        return static::where('client_id', $client_id)->get();
    }
}
