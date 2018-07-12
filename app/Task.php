<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['task_name', 'type_id', 'program_id'];

    public function types() 
    {
    	return $this->hasMany('App\TaskType', 'id', 'type_id');
    }

    public function programs()
    {
    	return $this->hasMany('App\Program', 'id', 'program_id');
    }
}
