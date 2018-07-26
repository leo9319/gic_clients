<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [ 'step_id', 'task_name', 'assigned_to', 'duration', 'file_upload', 'form_name'];

}
