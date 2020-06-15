<?php

namespace App\Models\V2;

class FileType extends BaseModel
{
	const IMAGE = 'Image';
	const SPEC_SHEET = 'Spec Sheet';
	const MANUALS = 'Manual';
	const FORM_UPLOAD = 'Form Upload';

	const UNRESTRICTED_TYPES = [
		self::IMAGE,
		self::SPEC_SHEET,
		self::MANUALS
	];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

	public static function getByPath(string $path) : ?self
	{
		return self::where('remote_path', $path)->first();
	}

	public function isRestricted() : bool
	{
		return !in_array($this->name, self::UNRESTRICTED_TYPES);
	}

	public static function isPathRestricted(string $path) : bool
	{
		if($fileType = self::getByPath($path)) {
			return $fileType->isRestricted();
		}
		return false;
	}

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

	public static function getByType(string $type) : self
	{
		return self::where('name', $type)->first();
	}
}
