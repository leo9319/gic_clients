<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CounselorRmTask extends Model
{
    protected $fillable = ['id', 'client_id', 'user_id', 'step_id', 'task_id', 'deadline', 'form_entry_id', 'uploaded_file_name', 'status', 'approved_by', 'priority', 'approval'];

    public function tasks() 
    {
    	return $this->hasMany('App\Task', 'id', 'task_id');
    }

    public static function updateStatus($task_id, $status) 
    {
    	if ($status) {

    		return static::where('id', $task_id)->update([
                'status' => 'pending',
                'approved_by' => NULL,
                'approval' => -1,
            ]);

    	} else {

    		return static::where('id', $task_id)->update(['status' => 'incomplete']);
    	}
    }
}
