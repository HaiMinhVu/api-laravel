<?php

namespace App\Pivots;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SpecSheet extends Pivot
{
    protected $table='spec_sheet';

    public $timestamps = false;
}
