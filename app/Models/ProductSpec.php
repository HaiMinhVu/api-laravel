<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductSpec extends Model
{
    protected $table='product_spec';

    public $timestamps = false;

    protected $with = ['list'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function list()
    {
        return $this->belongsTo(ListProductSpec::class, 'spec_id');
    }
}
