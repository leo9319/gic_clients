<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
	protected $fillable = ['user_id',
						   'province',
						   'language1',
						   'date1',
						   'date_of_test_result1',
						   'certificate_number',
						   'test_pin1',
						   'test_version',
						   'speaking1',
						   'listening1',
						   'reading1',
						   'writing1',
						   'language2',
						   'date2',
						   'certificate_number2',
						   'result_date2',
						   'test_version2',
						   'test_pin2',
						   'speaking2',
						   'listening2',
						   'reading2',
						   'writing2',
						   'we_three_years',
						   'we_ten_years',
						   'skill_trades',
						   'canadian_dollars',
						   'family_members',
						   'job_offer',
						   'currently_working',
						   'skill_type_three_years'];
	
    public static function userFileInformation($user_id)
    {
        return File::where('user_id', $user_id)->first();
    }
}
