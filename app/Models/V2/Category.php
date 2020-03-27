<?php

namespace App\Models\V2;

class Category extends BaseModel
{
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

	public function parent()
	{
		return $this->hasOneThrough(
			Category::class,
			CategoryHierarchy::class,
			'category_id',
			'id',
			'id',
			'parent_category_id'
		);
	}

	public function children()
	{
		return $this->hasManyThrough(
			Category::class,
			CategoryHierarchy::class,
			'parent_category_id',
			'id',
			'id',
			'category_id'
		);
	}
}
