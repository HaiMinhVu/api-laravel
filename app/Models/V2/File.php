<?php

namespace App\Models\V2;

use App\Pivots\V2\FileProduct;

class File extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];


    // Relations

    public function brand()
    {
		return $this->belongsTo(Brand::class);
	}

	public function products()
    {
		return $this->hasMany(Product::class)
					->using(FileProduct::class);
	}

	public function type()
	{
		return $this->belongsTo(FileType::class);
	}

	public function category()
	{
		return $this->hasOne(Category::class);
	}


	// Methods

	public function remotePath()
	{
		return "{$this->brand->slug}/{$this->type->remote_path}";
	}
}
