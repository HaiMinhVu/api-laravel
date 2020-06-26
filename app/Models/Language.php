<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends MasterList
{
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('language_scope', function($builder) {
            $builder->where('pid', 1);
        });
    }

    public function manuals()
    {
        return $this->belongsToMany(
            Manual::class,
            'manual_language',
            'language_id',
            'manual_id'
        );
    }

}
