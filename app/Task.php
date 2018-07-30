<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [ 'step_id', 'task_name', 'assigned_to', 'duration', 'file_upload', 'form_name'];

    public static function getAllTasks($step_id)
    {
    	return static::where('step_id', $step_id)->get();
    }

    public static function getUserTasks($step_id, $user_role)
    {
    	return static::where([
            'step_id' => $step_id,
            'assigned_to' => $user_role,
        ])->get(); 
    }

}
