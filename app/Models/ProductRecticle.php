<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReticle extends Model
{
    protected $table = 'product_reticle';

    protected $with = ['fileManager'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class, 'file_id', 'ID');
    }
}
