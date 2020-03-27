<?php

namespace App\Models\V2;

class CategoryHierarchy extends BaseModel
{
    // Relations

    public function brand()
    {
		return $this->belongsTo(Brand::class);
	}

	public function parentCategory()
	{
		return $this->belongsTo(Category::class, 'parent_category_id');
	}

	public function category()
	{
		return $this->belongsTo(Category::class);
	}
}
