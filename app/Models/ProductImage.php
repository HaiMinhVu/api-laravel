<?php

namespace App\Models;

use Illuminate\Database\Eloquent\{
    Builder,
    Model
};

class ProductImage extends Model
{
    protected $table='product_img';

    public $timestamps = false;

    protected $with = ['fileManager'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderByRaw('ISNULL(img_order), img_order ASC');
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function main()
    {
        return $this->belongsTo(ProductImageList::class, 'img_id');
    }

    public function thumb()
    {
        return $this->belongsTo(ProductImageList::class, 'thumb_img_id');
    }

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class, 'file_id', 'ID');
    }
}
