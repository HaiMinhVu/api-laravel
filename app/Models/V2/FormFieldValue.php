<?php

namespace App\Models\V2;

use Illuminate\Database\Eloquent\Model;

class FormFieldValue extends Model
{
    public function field()
    {
    	return $this->belongsTo(FormField::class);
    }

    public function submission()
    {
    	return $this->belongsTo(FormFieldSubmission::class);
    }
}
