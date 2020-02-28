<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ProductCategoryAssociation extends Pivot
{
    protected $table='product_category_association';

    public $timestamps = false;
}
