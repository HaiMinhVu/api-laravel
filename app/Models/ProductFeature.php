<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductFeature extends Model
{
    protected $table='product_feature';

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
