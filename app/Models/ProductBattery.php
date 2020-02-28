<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBattery extends Model
{
    protected $table='product_battery';

    protected $fillable=[
        'id',
        'product_id',
        'battery_id',
        'battery_order',
        'battery_qty',
        'included',
        'date_created',
        'uid_created',
        'date_modified',
        'uid_modified',
    ];

    public $timestamps = false;

    protected $with = ['list'];

    public function list()
    {
        return $this->belongsTo(ListProductBattery::class, 'battery_id');
    }

}
