<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductIncludedItems extends Model
{
    protected $table='product_included';

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

}
