<?php

namespace App\Models\V2;

class FormFieldSelectedOption extends BaseModel
{
	public $timestamps = false;

    public function submission()
    {
    	return $this->belongsTo(FormFieldSubmission::class);
    }

    public function option()
    {
    	return $this->belongsTo(FormFieldOption::class);
    }
}
