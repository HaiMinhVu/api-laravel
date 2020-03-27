<?php

namespace App\Models\V2;

class FormFieldSubmission extends BaseModel
{
	public $timestamps = false;

    public function form()
    {
    	return $this->belongsTo(Form::class);
    }

    public function value()
    {
    	return $this->hasOne(FormFieldValue::class);
    }

    public function selectedOption()
    {
    	return $this->hasOne(FormFieldSelectedOption::class);
    }
}
