<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductImageList extends Model
{
    protected $table='product_img_list';

    public $timestamps = false;

    public function productImage()
    {
        return $this->hasOne(ProductImage::class);
    }
}
