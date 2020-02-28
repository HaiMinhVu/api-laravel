<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ListRelatedProduct extends Pivot
{
    protected $table='list_related_product';

    public $timestamps = false;
}
