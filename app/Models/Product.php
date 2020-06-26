<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Pivots\{
    ListRelatedProduct,
    ProductCategoryAssociation,
    SpecSheet
};

class Product extends Model
{
    protected $hidden = ['pivot'];

    protected $table='product';

    protected $fillable=[
        'sku','nsid','Name','feature_name','manufacture','nsn','feature_desc',
        'store_desc','product_category_id','main_img_id','keywords','UPC',
        'market_priority','stock_status','start_date','date_created','sort_order',
        'status','hide_price','company_res','dealer_res','consumer_res','vendor_res',
        'uid_created','date_modified','uid_modified','site','site_bits'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('active_status', function($builder) {
            $builder->where('status', 1);
            $builder->active();
        });
    }

    public function loadRelations()
    {
        return $this->load(self::ALL_RELATIONS);
    }

    public function scopeByManufacturer($query, $manufacturer)
    {
        $manufacturers = Manufacturer::apiEndpoints();
        $manufacturerId = $manufacturers[$manufacturer];
        return $query->where('manufacture', $manufacturerId);
    }

    public function scopeWithAllRelations($query)
    {
        return $query->with([
            'mainImage',
            'manuals',
            'images',
            'includedItems',
            'category',
            'categories',
            'battery',
            'dealerResource',
            'relatedProducts',
            'features',
            'specs',
            'specSheets',
            'videos'
        ]);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('netsuiteProduct', function($q){
            $q->activeInWebstore();
            $q->current();
            $q->where('onlineprice', '!=', 0);
            $q->orderBy('startdate', 'DESC');
            $q->orderBy('sku', 'DESC');
        })->where('status', 1);
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'product_category_id');
    }

    public function categories()
    {
        return $this->hasManyThrough(
            ProductCategory::class,
            ProductCategoryAssociation::class,
            'product_id',
            'id',
            'id',
            'category_id'
        );

    }

    public function battery()
    {
        return $this->hasOne(ProductBattery::class);
    }

    public function dealerResource()
    {
        return $this->hasOne(ProductDealerResource::class);
    }

    public function features()
    {
        return $this->hasMany(ProductFeature::class);
    }

    public function specs()
    {
        return $this->hasMany(ProductSpec::class);
    }

    public function netsuiteProduct()
    {
        return $this->hasOne(NetsuiteProduct::class, 'nsid', 'nsid');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function videos()
    {
        return $this->hasMany(ProductVideo::class);
    }

    public function includedItems()
    {
        return $this->hasMany(ProductIncludedItems::class);
    }

    public function mainImage()
    {
        return $this->belongsTo(FileManager::class, 'main_img_id');
    }

    public function reticles()
    {
        return $this->hasMany(ProductReticle::class);
    }

    public function relatedProducts()
    {
        return $this->hasManyThrough(
            Product::class,
            ListRelatedProduct::class,
            'product_id',
            'id',
            'id',
            'related_product_id'
        );
    }

    // Old Relation
    // public function specSheets()
    // {
    //     return $this->hasManyThrough(
    //         FileManager::class,
    //         SpecSheet::class,
    //         'product_id',
    //         'id',
    //         'id',
    //         'file_id'
    //     );
    // }

    // Old Relation
    // public function manuals()
    // {
    //     return $this->hasManyThrough(
    //         FileManager::class,
    //         Manual::class,
    //         'product_id',
    //         'id',
    //         'id',
    //         'file_id'
    //     );
    // }

    public function specSheets()
    {
        return $this->belongsToMany(
            FileManager::class,
            'spec_sheet',
            'product_id',
            'file_id',
            'id',
            'ID'
        );
    }

    public function manuals()
    {
        return $this->belongsToMany(
            FileManager::class,
            'manuals',
            'product_id',
            'file_id',
            'id',
            'ID'
        );
    }

    public function downloads()
    {
        return $this->belongsToMany(
            FileManager::class,
            'product_download',
            'product_id',
            'file_manager_id',
            'id',
            'ID'
        );
    }

    public function syncFilesByType(string $type, array $ids)
    {
        if(in_array(strtolower($type), ['download', 'downloads'])) {
            return $this->downloads()->sync($ids);
        }
        if(in_array(strtolower($type), ['spec_sheet', 'spec_sheets'])) {
            $this->specSheets()->detach();
            return $this->specSheets()->attach($ids);
        }
        if(in_array(strtolower($type), ['manual', 'manuals'])) {
            $this->manuals()->detach();
            return $this->manuals()->attach($ids);
        }
    }

    public function files()
    {
        return $this->specSheets->merge($this->manuals->merge($this->downloads));
        // return $this->specSheets()->union($this->manuals());
        $specSheets = $this->specSheets;
        $query = $this->manuals()->union($query->getQuery());
        return $this->downloads()->union($query->getQuery());
        return $this->specSheets()->union($this->manuals()->union($this->downloads()));
    }

    public function featuredProduct()
    {
        return $this->hasOne(FeaturedProduct::class);
    }

    public function syncReticles($ids = [])
    {
        if(count($ids) > 0) {
            $productReticles = collect($ids)->map(function($id, $idx) {
                $productReticle = new ProductReticle;
                $productReticle->file_id = $id;
                $productReticle->product_id = $this->id;
                $productReticle->reticle_order = $idx+1;
                $productReticle->save();
                return $productReticle;
            });
            return $productReticles;
        }
    }

    public function syncImages($ids = [])
    {
        if(count($ids) > 0) {
            $productImages = collect($ids)->map(function($id, $idx) {
                $productImage = new ProductImage;
                $productImage->file_id = $id;
                $productImage->img_id = $id;
                $productImage->product_id = $this->id;
                $productImage->img_order = $idx+1;
                $productImage->save();
                return $productImage;
            });
            return $productImages;
        }
    }

}
