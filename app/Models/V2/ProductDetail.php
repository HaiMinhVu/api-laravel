<?php

namespace App\Models\V2;

class ProductDetail extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'netsuite_id', 
    	'start_date',
    	'end_date',
    	'eccn',
    	'ccats',
    	'online_price',
    	'quantity',
    	'taxable',
    	'weight',
    	'weight_units',
    	'auth_dealer_price',
    	'buying_group_price',
    	'dealer_price',
    	'dealer_dist_price',
    	'dis_price',
    	'dropship_price',
    	'gov_price',
    	'msrp',
    	'specials',
    	'online_price',
    	'backordered',
    	'product_dimensions',
    	'active_online',
    	'active',
    	'sku',
    	'upc'
    ];

    
    // Relations

    public function product()
    {
    	return $this->belongsTo(Product::class);
    }
}
