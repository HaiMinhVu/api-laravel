<?php

namespace App\Models\V2;

class FormField extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    public $timestamps = false;

    public function form()
    {
    	return $this->belongsTo(Form::class);
    }

    public function file()
    {
        // return $this->
    }

    public function options()
    {
    	return $this->hasMany(FormFieldOption::class);
    }

    public function submissions()
    {
    	return $this->hasMany(FormFieldSubmission::class);
    }

    public function type()
    {
    	return $this->belongsTo(FormFieldType::class, 'form_field_type_id');
    }

    public function isFile()
    {
        return $this->type->isFile();
    }

    public function values()
    {
    	return $this->hasMany(FormFieldValue::class);
    }

    public static function getTypeById(?int $id) : ?string
    {
        try {
            return self::with('type')->find($id)->type->name;
        } catch(\Exception $e) {
            return null;
        }
    }
}
