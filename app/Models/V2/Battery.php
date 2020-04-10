<?php

namespace App\Models\V2;

class Battery extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    
    // Relations

    public function product()
    {
		return $this->belongsTo(Product::class)
					->using(BatteryProduct::class)
					->withPivot(['included', 'quantity']);
	}
}
