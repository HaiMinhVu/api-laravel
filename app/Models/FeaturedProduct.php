<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class FeaturedProduct extends Model
{
    protected $table='featured_product';

    public $timestamps = false;

    protected $with = ['product'];

    protected $fillable = [
        'product_id',
        'file_id',
        'product_feature_order',
        'description',
        'featured_product_image',
        'included',
        'date_created',
        'uid_created',
        'date_modified',
        'uid_modified',
    ];

    public function scopeIsParent($query)
    {
        return $query->where('pid', 0)->orWhereNull('pid');
    }

    public function featuredProducts()
    {
        return $this->hasMany(FeaturedProduct::class, 'pid')->whereHas('product', function($q){
            $q->active();
        });
    }

    public function parent()
    {
        return $this->belongsTo(FeaturedProduct::class, 'pid');
    }

    public function children() 
    {
        return $this->hasMany(FeaturedProduct::class, 'pid')->orderBy('product_feature_order');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function productWithoutGlobalScopes()
    {
        return $this->product()->withoutGlobalScopes();
    }

    public function associateProductsBySkus(?array $skus) 
    {
        $order = 1;
        foreach($skus as $sku) {
            if($product = Product::withoutGlobalScopes()->where('sku', $sku)->first()) {
                $item = $this->children()->create([]);
                $item->product_feature_order = $order;
                $item->product()->associate($product);
                $item->save();
            }
            $order++;
        }
    }
}
