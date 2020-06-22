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

    public static function apiEndpoints()
    {
        return Cache::remember('manufacturer_endpoints', 3600, function () {
            $manufacturers = self::all();
            return $manufacturers->mapWithKeys(function($manufacturer){
                return [$manufacturer->slug => $manufacturer->id];
            });
        });
    }

    public static function cachedAll()
    {
        return Cache::remember('manufacturer_all', 3600, function() {
            return self::all();
        });
    }

    public static function findByPrefix(string $prefix)
    {
        $all = self::cachedAll();
        return $all->first(function($manufacturer) use ($prefix){
            return $manufacturer->prefix == $prefix;
        });
    }

    public static function findIdByPrefix(string $prefix)
    {
        $manufacturer = self::findByPrefix($prefix);
        return optional($manufacturer)->id;
    }

    public static function findByKey($key)
    {
        $manufacturers = self::cachedAll();
        return $manufacturers->first(function($manufacturer) use ($key) {
            return Str::kebab($manufacturer->name) == $key;
        });
    }

    public static function siteByKey($key)
    {
        return optional(self::findByKey($key))->site;
    }

    public function productCategories()
    {
        return $this->hasMany(ProductCategory::class, 'manufacture');
    }
}
