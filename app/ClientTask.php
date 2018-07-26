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
    public function taskName(){
        return $this->hasOne('App\Task','id','task_id');
    }
}
