<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
	protected $fillable = ['activity_id', 'activity_type', 'comment', 'commenter_id']; 

	public function users()
	{
		return $this->hasMany('App\User', 'id', 'commenter_id');
	}

    public static function getAllTaskComment($task_id)
    {
        return static::where([
            'activity_id' => $task_id,
            'activity_type' => 'task',
        ])->get();
    }

    public static function getAllAppointmentComment($appointment_id)
    {
    	return static::where([
    		'activity_id' => $appointment_id,
    		'activity_type' => 'appointment',
    	])->get();
    }
}
