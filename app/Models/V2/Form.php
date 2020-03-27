<?php

namespace App\Models\V2;

class Form extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public function fields()
    {
    	return $this->hasMany(FormField::class);
    }

    public function submissions()
    {
    	return $this->hasMany(FormSubmission::class);
    }

    public function scopeWithFormRelations($query)
    {
    	return $query->with(['fields.options']);
    }

}
