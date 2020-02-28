<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductVideo extends Model
{
    const VIDEO_URI = 'https://www.youtube.com/embed/';

    protected $table = 'youtube_product';

    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function url()
    {
        return self::VIDEO_URI.$this->you_tube_ID;
    }

}
