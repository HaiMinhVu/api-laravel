<?php

namespace App\Pivots\V2;

use Illuminate\Database\Eloquent\Relations\Pivot;

class BasePivot extends Pivot
{
    /**
     * The connection name for the pivot.
     *
     * @var string
     */
    protected $connection = 'cms';
}
