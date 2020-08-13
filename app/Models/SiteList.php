<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SiteList extends Model
{
    protected $table='site_list';

    public $timestamps = false;

    protected $fillable = ['label'];

    public function files()
    {
    	return $this->hasMany(FileManager::class, 'site_id');
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class, 'prefix', 'prefix');
    }
}
