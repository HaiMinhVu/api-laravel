<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class SiteList extends Model
{
    protected $table='site_list';

    public $timestamps = false;


    public function files()
    {
    	return $this->hasMany(FileManager::class, 'site_id');
    }
}
