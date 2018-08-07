<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
	protected $fillable = ['client_id', 'step_id', 'task_id', 'deadline', 'form_entry_id', 'uploaded_file_name', 'status', 'priority', 'approval'];

    public function tasks() 
    {
    	return $this->hasMany('App\Task', 'id', 'task_id');
    }

    public static function getClientTask($step_id, $client_id)
    {
    	return static::where([
            'client_id' => $client_id,
            'step_id' => $step_id,
        ])->get();
    }

    public static function updateStatus($task_id, $status)
    {
        if ($status == 'complete') {
            return ClientTask::find($task_id)->update([
                'status' => 'pending',
                'approval' => -1,
                'approved_by' => '',
            ]);
        }

        else if ($status == 'incomplete')
        {
            return ClientTask::find($task_id)->update([
                'status' => 'incomplete',
                'approval' => -1,
                'approved_by' => '',
            ]);
        }
    }
}
