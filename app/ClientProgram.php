<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientProgram extends Model
{
	protected $fillable = ['client_id', 'program_id', 'steps'];

    public static function programs($client_id)
    {
    	return ClientProgram::where('client_id', $client_id)->get();
    }

    public function programInfo()
    {
        return $this->hasMany('App\Program', 'id', 'program_id');
    }

    public static function selectedPrograms($client_id)
    {
    	return static::where('client_id', $client_id)->get();
    }

    public static function assignedSteps($program_id, $client_id)
    {
        return static::where([
            'client_id' => $client_id,
            'program_id' => $program_id,
        ])->first();
    }
}
