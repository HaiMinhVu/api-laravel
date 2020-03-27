<?php

namespace App\Models\V2;

class Tag extends BaseModel
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
    	return $this->belongsToMany(Product::class)
    				->using(ProductTag::class);
    }
}
