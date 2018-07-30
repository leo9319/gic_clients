<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = ['program_id', 'step_name', 'order'];

    public static function getProgramFirstStep($program_id) 
    {
    	return static::where('program_id', $program_id)->orderBy('order', 'asc')->first();
    }

    public static function getProgramAllStep($program_id) 
    {
    	return static::where('program_id', $program_id)->orderBy('order', 'asc')->get();
    }
}
