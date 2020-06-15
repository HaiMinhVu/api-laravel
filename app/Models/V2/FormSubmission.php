<?php

namespace App\Models\V2;

class FormSubmission extends BaseModel
{
	protected $with = [
		'form',
		'fieldSubmissions.value',
		'fieldSubmissions.selectedOption'
	];

    public function form()
    {
    	return $this->belongsTo(Form::class);
    }

	public function brand()
	{
		return $this->belongsTo(Brand::class);
	}

    public function fieldSubmissions()
    {
    	return $this->hasMany(FormFieldSubmission::class);
    }

	public function getValueByLabel(string $label) : ?string
	{
		$submission = $this->fieldSubmissions->first(function($fieldSubmission) use ($label) {
			return $fieldSubmission->formField->name == $label;
		});
		return optional($submission)->getValue();
	}

	public function getSimpleSubmissionData()
	{
		return $this->fieldSubmissions->map(function($fieldSubmission){
			return [
				'field' => optional($fieldSubmission->formField)->description,
				'value' => $fieldSubmission->getValue()
			];
		});
	}

}
