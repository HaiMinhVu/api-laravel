<?php

namespace App\Models\V2;

class Brand extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'slug'];

    
    // Relations

    public function products()
    {
		return $this->hasMany(Product::class);
	}

	public function files()
	{
		return $this->hasMany(File::class);
	}

	public function categoryHierarchies()
	{
		return $this->hasMany(CategoryHierarchy::class);
	}
}
