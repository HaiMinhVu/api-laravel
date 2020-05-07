<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class NetsuiteProduct extends Model
{
    const IS_ACTIVE_IN_WEBSTORE = 'Yes';
    const NS_PRODUCT_DATEFORMAT = 'm/d/Y';

    protected $table='netsuite_products';

    protected $primaryKey = 'nsid';

    protected $fillable = [
        "nsid", "active_in_webstore", "inactive", "ns_product_category", "startdate", "enddate", 
        "sku", "featured_description", "UPC", "description", "ECCN", "CCATS", "online_price", "map",
        "total_quantity_on_hand","taxable","weight","weight_units","authdealerprice","buyinggroupprice",
        "dealerprice","dealerdistprice","disprice","dropshipprice","govprice", "msrp", "specials",
        "onlineprice", "backordered", "product_sizing"
    ];

    public $timestamps = false;

    protected $with = ['productCategory'];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class, 'ns_product_category', 'label');
    }

    public function scopeActiveInWebstore($query)
    {
        return $query->where('active_in_webstore', self::IS_ACTIVE_IN_WEBSTORE);
    }

    public function scopeCurrent($query)
    {
        $now = (Carbon::now())->format('m/d/Y');

        return $query->where(function($q) use ($now) {
            $q->where(function($q) use ($now){
                $q->whereNull('enddate');
                $q->where('startdate', '>=', $now);
            })->orWhere(function($q) use ($now) {
                $q->where('startdate', '>=', $now);
                $q->where('enddate', '<=', $now);
            });
        });
    }

    public static function updateFromRemote($data)
    {
        try {
            $data = self::transformRemoteDataV1($data);
            self::updateOrCreate(['nsid' => $data['nsid']], $data);
            return true;
        } catch(\Exception $e) {
            Log::debug($e->getMessage());
            return false;
        }
    }

    private static function transformRemoteDataV1($remoteData)
    {
        $data = optional($remoteData);
        $pricing = optional($data['pricingMatrix']);
        return [
            "nsid" => $data['internalId'],
            "active_in_webstore" => $data['isOnline'] ? 'Yes' : 'No',
            "inactive" => $data['isActive'] ? 'Yes' : 'No',
            "ns_product_category" => $data['netsuiteCategory'] ?? 'Uncategorized',
            "startdate" => self::parseTimeV1($data['startDate']),
            "enddate" => self::parseTimeV1($data['endDate']),
            "sku" => $data['itemId'] ?? '',
            "featured_description" => $data['salesDescription'] ?? '',
            "UPC" => $data['upcCode'] ?? '',
            "description" => $data['purchaseDescription'] ?? '',
            "ECCN" => $data['eccn'] ?? '',
            "CCATS" => $data['ccats'] ?? '',
            "online_price" => $pricing['onlinePrice'] ?? 0,
            "map" => $pricing['map'] ?? 0,
            "total_quantity_on_hand" => $data['quantityOnHand'] ?? 0,
            "taxable" => $data['isTaxable'] ? "Yes" : "No",
            "weight" => $data['weight'] ?? 0.00,
            "weight_units" => str_replace('_', '', $data['weightUnit']),
            "authdealerprice" => $pricing['authorizedDealer'] ?? 0,
            "buyinggroupprice" => $pricing['buyingGroup'] ?? 0,
            "dealerprice" => $pricing['dealer'] ?? 0,
            "dealerdistprice" => $pricing['dealerDistributor'] ?? 0,
            "disprice" => $pricing['distributor'] ?? 0,
            "dropshipprice" => $pricing['dropShip'] ?? 0,
            "govprice" => $pricing['government'] ?? 0,
            "msrp" => $pricing['msrp'] ?? 0,
            "specials" => $pricing['specials'] ?? 0,
            "onlineprice" => $pricing['onlinePrice'] ?? 0,
            "backordered" => $data['quantityBackOrdered'] ?? 0,
            "product_sizing" => $data['productSizing'] ?? ''
        ];
    }

    private static function parseTimeV1($timeString) 
    {
        if($timeString) {
            return Carbon::parse($timeString)->setTimezone(config('app.timezone'))->format(self::NS_PRODUCT_DATEFORMAT);
        }
        return '';
    }

}
