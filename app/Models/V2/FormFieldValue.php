<?php

namespace App\Models\V2;

class FormFieldValue extends BaseModel
{
	public $timestamps = false;

    public function field()
    {
    	return $this->belongsTo(FormField::class);
    }

    public function submission()
    {
    	return $this->belongsTo(FormFieldSubmission::class);
    }
}
