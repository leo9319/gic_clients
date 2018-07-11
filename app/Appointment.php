<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = ['appointer_id', 'client_id', 'app_date', 'app_time'];
}
