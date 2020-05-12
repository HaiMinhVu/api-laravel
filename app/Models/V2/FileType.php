<?php

namespace App\Models\V2;

class FileType extends BaseModel
{
	const IMAGE = 'Image';
	const SPEC_SHEET = 'Spec Sheet';
	const MANUALS = 'Manual';
	const FORM_UPLOAD = 'Form Upload';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    
    // Relations

    public function products()
    {
		return $this->hasMany(Product::class);
	}


	// Scopes 

	public function scopeImages($query)
	{
		return $query->where('name', self::IMAGE);
	}

	public function scopeSpecSheets($query)
	{
		return $query->where('name', self::SPEC_SHEET);
	}

	public function scopeManuals($query)
	{
		return $query->where('name', self::MANUAL);
	}

	public function scopeFormUpload($query)
	{
		return $query->where('name', self::FORM_UPLOAD);
	}
}
