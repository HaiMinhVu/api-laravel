<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manual extends Model
{
    protected $table='manuals';

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active_status', function($builder) {
            $builder->where('status', 1);
        });
    }
}
