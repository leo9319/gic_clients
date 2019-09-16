<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetSetting extends Model
{
    protected $guarded = [];

    public function hasTarget($department)
    {
    	if($department == 'counseling') {
    		return ($this->counselor_count > 0 ? true : false);
    	} else {
    		return ($this->rm_count > 0 ? true : false);
    	}
    }
}
