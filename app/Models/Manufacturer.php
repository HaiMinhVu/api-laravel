<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Pivots\ProductCategoryAssociation;
use Illuminate\Support\Str;
use Cache;

class Manufacturer extends Model
{
    protected $table='list_manufacture';

    public $timestamps = false;

    // Inconsistencies in the DB require similar functionality in this model and ProductCategory model
    public static function apiEndpoints()
    {
        return Cache::remember('manufacturer_endpoints', 3600, function () {
            $manufacturers = self::all();
            return $manufacturers->mapWithKeys(function($manufacturer){
                return [Str::kebab($manufacturer->name) => $manufacturer->id];
            });
        });
    }

    public static function findByKey($key)
    {
        $manufacturers = self::all();
        return $manufacturers->first(function($manufacturer) use ($key) {
            return Str::kebab($manufacturer->name) == $key;
        });
    }

    public static function siteByKey($key)
    {
        return optional(self::findByKey($key))->site;
    }
}
