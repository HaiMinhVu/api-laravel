<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeaturedProduct extends Model
{
    protected $table='featured_product';

    public $timestamps = false;

    protected $with = ['product'];

    public function featuredProducts()
    {
        return $this->hasMany(FeaturedProduct::class, 'pid');
    }

    public function parent()
    {
        return $this->belongsTo(FeaturedProduct::class, 'pid');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id')->withoutGlobalScopes();
    }
}
