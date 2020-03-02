<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavCat extends Model
{
    protected $table='nav_cat';

    public $timestamps = false;

    public function  scopeIsParent($query)
    {
    	return $query->where('parent', 0);
    }

    public function items()
    {
        return $this->hasMany(NavCat::class, 'parent');
    }
}
