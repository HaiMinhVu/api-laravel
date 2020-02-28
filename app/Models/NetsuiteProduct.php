<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class NetsuiteProduct extends Model
{
    const IS_ACTIVE_IN_WEBSTORE = 'Yes';

    protected $table='netsuite_products';

    public $timestamps = false;

    protected $with = ['productCategory'];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'ns_product_category', 'label');
    }

    public function scopeActiveInWebstore($query)
    {
        return $query->where('active_in_webstore', self::IS_ACTIVE_IN_WEBSTORE);
    }

    public function scopeCurrent($query)
    {
        $now = (Carbon::now())->format('m/d/Y');

        return $query->where(function($q) use ($now) {
            $q->where(function($q) use ($now){
                // $q->whereNull('startdate');
            // })->orWhere(function($q) use ($now) {
                $q->whereNull('enddate');
                $q->where('startdate', '>=', $now);
            })->orWhere(function($q) use ($now) {
                $q->where('startdate', '>=', $now);
                $q->where('enddate', '<=', $now);
            });
        });
    }

}
