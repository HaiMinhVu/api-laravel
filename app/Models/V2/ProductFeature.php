<?php

namespace App\Models\V2;

class ProductFeature extends BaseModel
{
   	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'order'];

    
    // Relations

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
