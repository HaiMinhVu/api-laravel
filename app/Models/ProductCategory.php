<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Pivots\ProductCategoryAssociation;
use Illuminate\Support\Str;
use App\Models\{
    FileManager,
    Manufacturer
};

class ProductCategory extends Model
{
    use SoftDeletes;

    protected $table='product_category';

    public $timestamps = false;

    protected $fillable=[
        'label',
        'parent',
        'thumbnail',
        'manufacture',
        'short_description',
        'long_description',
        'pc_text'
    ];

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

    public function scopeByManufacturer($query, $manufacturerSlug)
    {
        $query->whereHas('manufacturer', function($q) use($manufacturerSlug){
            $q->where('slug', $manufacturerSlug);
        });
    }

    public function scopeTopLevel($query)
    {
        $query->whereHas('parentCategory', function($q){
            $q->isParent();
        });
    }

    public function scopeHasParent($query)
    {
        $query->where('parent', '>', 0);
    }

    public function scopeIsParent($query)
    {
        $query->where('parent', 0);
    }

    public function scopeHasActiveProducts($query)
    {
        return $query->whereHas('products', function($q){
            $q->active();
        });
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

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'manufacture');
    }

    public function imageUrl()
    {
        return ($this->fileManager && $this->fileManager->url()) ? $this->fileManager->url() : FileManager::defaultImage();
    }
}
