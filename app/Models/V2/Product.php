<?php

namespace App\Models\V2;

use App\Pivots\V2\{
	CategoryProduct,
	FileProduct,
	ProductTag
};

class Product extends BaseModel
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description'];

    
    // Relations

    public function battery()
    {
    	return $this->hasOne(Battery::class)
    				->using(BatteryProduct::class)
    				->withPivot(['included', 'quantity']);
    }

    public function brand()
    {
    	return $this->belongsTo(Brand::class);
    }

    public function tags()
    {
    	return $this->hasMany(Tag::class)
    				->using(ProductTag::class);
    }

    public function files()
    {
    	return $this->hasMany(File::class)
    				->using(FileProduct::class);
    }

    public function categories()
    {
    	return $this->hasMany(Category::class)
    				->using(CategoryProduct::class);
    }

    public function detail()
    {
    	return $this->hasOne(ProductDetail::class);
    }

    public function features()
    {
    	return $this->hasMany(ProductFeature::class);
    }
}
