<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSms extends Model
{
    protected $fillable = ['type', 'subject', 'text_body', 'from', 'to'];
}
