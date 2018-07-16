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
}
