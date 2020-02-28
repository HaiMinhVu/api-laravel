<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDealerResource extends Model
{
    protected $table='product_dealer_resources';

    public $timestamps = false;

    public function products()
    {
        return $this->hasOne(Product::class);
    }
}
