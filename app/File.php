<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	protected $fillable = ['user_id', 'programs', 'visa_type', 'first_name', 'last_name', 'mobile', 'dob', 'email', 'maritial_status', 'education', 'university_attended', 'profession', 'work_experience', 'field_of_work', 'hear_about_us', 'foreign_country_visited', 'created_at', 'updated_at'];
	
    public static function userFileInformation($user_id)
    {
        return File::where('user_id', $user_id)->first();
    }
}
