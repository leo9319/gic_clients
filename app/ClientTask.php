<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTask extends Model
{
    public function tasks() 
    {
    	return $this->hasMany('App\Task', 'id', 'task_id');
    }
}
