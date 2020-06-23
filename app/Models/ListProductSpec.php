<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\UTF8Convertible;

class ListProductSpec extends Model
{
    use UTF8Convertible;

    protected $table='list_product_spec';

    public $timestamps = false;

    public function productSpec()
    {
        return $this->hasOne(ProductSpec::class);
    }
}
