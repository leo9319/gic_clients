<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['program_id', 'step_name', 'step_number'];

    public static function getProgramFirstStep($program_id) 
    {
        return static::where('program_id', $program_id)->orderBy('step_number', 'asc')->first();
    }

    public static function getProgramAllStep($program_id) 
    {
    	return static::where('program_id', $program_id)->orderBy('step_number', 'asc')->get();
    }

    public static function getStepInfo($program_id, $order) 
    {
    	return static::where(['program_id' => $program_id, 'step_number' => $order])->first();
    }

    public function targetSetting()
    {
        return $this->hasOne('App\TargetSetting');
    }
}
