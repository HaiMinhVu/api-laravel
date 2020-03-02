<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Pivots\ProductCategoryAssociation;
use Illuminate\Support\Str;
use App\Models\FileManager;
use Cache;

class ProductCategory extends Model
{
    protected $table='product_category';

    public $timestamps = false;

    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            ProductCategoryAssociation::class,
            'category_id',
            'id',
            'id',
            'product_id'
        );
    }

    public function scopeByManufacturer($query, $manufacturer)
    {
        $manufacturers = self::apiEndpoints();
        $manufacturerId = $manufacturers[$manufacturer];
        return $query->where('manufacture', $manufacturerId);
    }

    public function scopeTopLevelCategoriesByManufacturer($query, $manufacturer)
    {
        $manufacturers = self::apiEndpoints();
        $manufacturerId = $manufacturers[$manufacturer];
        return $query->where('parent', $manufacturerId);
    }

    public function scopeHasParent($query)
    {
        return $query->where('parent', '>', 0);
    }

    public function parentCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'parent');
    }

    public function subCategories()
    {
        return $this->hasMany(ProductCategory::class, 'parent');
    }

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class, 'thumbnail');
    }

    // Inconsistencies in the DB require similar functionality in this model and Manufacturer model
    public static function apiEndpoints()
    {
        return Cache::remember('product_category_endpoints', 3600, function () {
            $categories = self::where('parent', 0)->get();
            return $categories->mapWithKeys(function($category){
                return [Str::kebab($category->label) => $category->id];
            });
        });
    }

    public function imageUrl()
    {
        return ($this->fileManager && $this->fileManager->url()) ? $this->fileManager->url() : FileManager::defaultImage();
    }
}
