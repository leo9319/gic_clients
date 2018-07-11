<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CounsellorClient extends Model
{
	protected $fillable = ['client_id', 'counsellor_id'];
	
    public function users()
    {
    	return $this->hasMany('App\User', 'id', 'counsellor_id');
    }
}
