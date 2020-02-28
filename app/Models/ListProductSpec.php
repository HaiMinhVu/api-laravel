<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListProductSpec extends Model
{
    protected $table='list_product_spec';

    public $timestamps = false;

    public function productSpec()
    {
        return $this->hasOne(ProductSpec::class);
    }
}
