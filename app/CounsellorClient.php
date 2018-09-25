<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CounsellorClient extends Model
{
	protected $fillable = ['client_id', 'counsellor_id'];
	
    public function users()
    {
    	return $this->hasMany('App\User', 'id', 'counsellor_id');
    }

    public function clients()
    {
    	return $this->hasMany('App\User', 'id', 'client_id');
    }

    public static function fileOpenedThisMonth($counsellor_id)
    {
    	return static::where('counsellor_id', $counsellor_id)->whereMonth('created_at', '=', Carbon::now()->month)->get();;
    }

    public static function totalFilesOpened($counsellor_id)
    {
    	return static::where('counsellor_id', $counsellor_id)->get();
    }

    public static function assignCounselorToClient($all_counselors, $client_id) 
    {
        foreach ($all_counselors as $counselor) {
            static::create([
                'client_id' => $client_id,
                'counsellor_id' => $counselor
            ]);
        }
    }

    public static function assignedCounselor($client_id)
    {
        return static::where('client_id', $client_id)->get();
    }

}
