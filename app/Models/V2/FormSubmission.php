<?php

namespace App\Models\V2;

class FormSubmission extends BaseModel
{
	public $timestamps = false;

	protected $with = [
		'form', 
		'fieldSubmissions.value', 
		'fieldSubmissions.selectedOption'
	];

    public function form()
    {
    	return $this->belongsTo(Form::class);
    }

    public function fieldSubmissions()
    {
    	return $this->hasMany(FormFieldSubmission::class);
    }

}
