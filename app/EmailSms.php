<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailSms extends Model
{
    protected $fillable = ['type', 'subject', 'text_body', 'from', 'to'];

    public static function setEmailRecord($subject, $text_body, $from, $to) 
    {
    	return static::create([
    		'type' => 'email',
    		'subject' => $subject,
    		'text_body' => $text_body,
    		'from' => $from,
    		'to' => $to,
    	]);
    }
}
