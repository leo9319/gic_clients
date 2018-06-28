<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
	protected $fillable = [
		'client_id',
		'program_id',
		'task_id',
		'assignee_id',
		'assigned_date',
		'status',
		'priority',
		'uploaded_file_name'
	];

    public function tasks() 
    {
    	return $this->hasMany('App\Task', 'id', 'task_id');
    }
}
