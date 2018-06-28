<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistrationForm extends Model
{
    protected $table = 'client_registration_form';

    protected $fillable = [
    	'programs',
		'visa_types',
		'first_name',
		'last_name',
		'mobile',
		'email',
		'dob',
		'marital_status',
		'education',
		'university',
		'profession',
		'experience',
		'field_of_work',
		'hear_about_us',
		'other_countries'
    ];
}
