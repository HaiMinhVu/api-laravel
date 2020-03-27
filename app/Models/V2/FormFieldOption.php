<?php

namespace App\Models\V2;

class FormFieldOption extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public $timestamps = false;

    public function field()
    {
    	return $this->belongsTo(FormField::class);
    }

    public function selectedOptions()
    {
    	return $this->hasMany(FormFieldSelectedOption::class);
    }


}
