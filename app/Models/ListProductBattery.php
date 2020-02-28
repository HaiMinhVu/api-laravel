<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ListProductBattery extends Model
{
    protected $table='list_product_battery';

    public $timestamps = false;

    public function productSpec()
    {
        return $this->hasMany(ProductBattery::class);
    }
}
