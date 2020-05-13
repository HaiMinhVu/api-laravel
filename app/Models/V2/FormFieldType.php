<?php

namespace App\Models\V2;

class FormFieldType extends BaseModel
{
    const SELECTABLE = ['radio', 'select', 'checkbox'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public $timestamps = false;

    public function fields()
    {
    	return $this->hasMany(FormField::class);
    }

    public static function getByName($name)
    {
        return self::where('name', $name)->first();
    }
}
