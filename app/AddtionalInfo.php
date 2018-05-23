<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AddtionalInfo extends Model
{
	protected $fillable = ['user_id', 'email', 'question1', 'answer1', 'question2', 'answer2', 'question3', 'answer3', 'question4', 'answer4', 'gender', 'dob', 'marital_status', 'country_ob', 'city_ob', 'passport_or_nid', 'document_id', 'document_number', 'country_of_issue', 'issue_date', 'expiry_date', 'immigration_before', 'citizenship', 'residence', 'family_members', 'relative', 'relation', 'relative_residence', 'primary_language', 'representative', 'rep_last_name', 'rep_first_name', 'rep_email', 'rep_id_number', 'nomination', 'noc_code', 'noc_start_date', 'certificate_of_qualification'];
	
    protected $table = 'addition_info';
}
