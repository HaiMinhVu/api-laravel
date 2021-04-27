<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Manual extends Model
{
    protected $table='manuals';

    public $timestamps = false;

    public function languages()
    {
        return $this->belongsToMany(
            MasterList::class,
            'manual_language',
            'manual_id',
            'language_id'
        );
    }

    public function fileManager()
    {
        return $this->belongsTo(
            FileManager::class,
            'file_id',
            'ID'
        );
    }

    public function product()
    {
        return $this->hasOne(
            Product::class,
            'id',
            'product_id'
        )->select('id', 'sku', 'nsid');
    }
}
