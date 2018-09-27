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

    public static function getAllSpouseTaskComment($task_id)
    {
        return static::where([
            'activity_id' => $task_id,
            'activity_type' => 'spouse task',
        ])->get();
    }

    public static function getAllAppointmentComment($appointment_id)
    {
    	return static::where([
    		'activity_id' => $appointment_id,
    		'activity_type' => 'appointment',
    	])->get();
    }

    public static function getAllCommentsOfCommenter($commenter_id)
    {
        return static::where('commenter_id', $commenter_id)->get();
    }

    public function getActivityName($activity_type, $activity_id)
    {
        if($activity_type == 'task') {

            $task_id = ClientTask::find($activity_id)->task_id;

            return Task::find($task_id)->task_name;

        } else {

            return Appointment::find($activity_id)->title;

        }
        
    }
}
