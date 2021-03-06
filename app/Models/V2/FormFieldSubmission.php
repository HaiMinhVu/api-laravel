<?php

namespace App\Models\V2;

class FormFieldSubmission extends BaseModel
{
	public $timestamps = false;

    public function form()
    {
    	return $this->belongsTo(Form::class);
    }

	public function formField()
	{
		return $this->belongsTo(FormField::class);
	}

    public function value()
    {
    	return $this->hasOne(FormFieldValue::class);
    }

    public function selectedOption()
    {
    	return $this->hasOne(FormFieldSelectedOption::class);
    }

	public function files()
	{
		return $this->belongsToMany(File::class);
	}

	public function getValue()
	{
		if($this->formField->type->isFile()) {
			return $this->files()->first()->name;
		} elseif($this->formField->type->isSelectable()) {
			return optional($this->selectedOption->option)->name;
		} else {
			return $this->value->name;
		}
	}
}
