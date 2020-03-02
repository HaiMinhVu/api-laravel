<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SliderImage extends Model
{
    protected $table='slider_image';

    public $timestamps = false;

    protected $with = ['images', 'fileManager'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('ordered', function($builder) {
            $builder->orderBy('slider_order');
        });
    }

    public function scopeIsParent($query)
    {
        return $query->where('pid', 0);
    }

    public function images()
    {
        return $this->hasMany(SliderImage::class, 'pid');
    }

    public function parent()
    {
        return $this->belongsTo(SliderImage::class, 'pid');
    }

    public function fileManager()
    {
        return $this->belongsTo(FileManager::class, 'file_id', 'ID');
    }
}
