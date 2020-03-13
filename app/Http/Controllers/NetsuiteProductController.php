<?php

namespace App\Http\Controllers;

use App\Models\NetsuiteProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NetsuiteProductController extends Controller
{
    const NS_PRODUCT_DATEFORMAT = 'm/d/Y';

    /**
     * Mass update NetsuiteProducts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(Request $request)
    {
                            // $netsuiteProduct = $request->all()[0];
                            // return NetsuiteProduct::updateFromRemote($netsuiteProduct);
        return collect($request->all())->map(function($netsuiteProduct){
            return NetsuiteProduct::updateFromRemote($netsuiteProduct);
            // return array_keys($netsuiteProduct);
                    // $netsuiteProduct = $request->all()[0];

            // $netsuiteId = $netsuiteProduct['internalId'];
            // $netsuiteUpdatedProduct = NetsuiteProduct::updateOrCreate(['nsid' => $netsuiteId], $this->transformRemoteDataV1($netsuiteProduct));
            // return $netsuiteUpdatedProduct->nsid;
            // $netsuiteProduct2 = NetsuiteProduct::without('productCategory')->where('nsid', $netsuiteId)->first();
            // return [
            //     $netsuiteProduct, 
            //     $netsuiteProduct2
            // ];
            // return NetsuiteProduct::updateOrCreate(['nsid' => $netsuiteId], $netsuiteProduct);
            // return $netsuiteProduct->manufacturer;
        })->filter()->count();
    }

    private function transformRemoteDataV1($remoteData)
    {
        $data = optional($remoteData);
        $pricing = optional($data['pricingMatrix']);
        $newData = [
            "nsid" => $data['internalId'],
            "active_in_webstore" => $data['isOnline'] ? 'Yes' : 'No',
            "inactive" => $data['isActive'] ? 'Yes' : 'No',
            "ns_product_category" => $data['netsuiteCategory'] ?? 'Uncategorized',
            "startdate" => $this->parseTimeV1($data['startDate']),
            "enddate" => $this->parseTimeV1($data['endDate']),
            "sku" => $data['itemId'],
            "featured_description" => $data['salesDescription'],
            "UPC" => $data['upcCode'] ?? 'N/A',
            "description" => $data['purchaseDescription'],
            "ECCN" => $data['eccn'],
            "CCATS" => $data['ccats'],
            "online_price" => $pricing['onlinePrice'] ?? 0,
            "map" => $pricing['map'] ?? 0,
            "total_quantity_on_hand" => $data['quantityOnHand'] ?? 0,
            "taxable" => $data['isTaxable'] ? "Yes" : "No",
            "weight" => $data['weight'],
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
            "product_sizing" => $data['productSizing']
        ];

        foreach([
            'product_sizing', 
            'UPC', 
            'ECCN', 
            'CCATS', 
            'featured_description', 
            'description', 
            'weight'
        ] as $required) {
            if(!$newData[$required]) unset($newData[$required]);
        }
        return $newData;
    }

    private function parseTimeV1($timeString) 
    {
        return Carbon::parse($timeString)->setTimezone(config('app.timezone'))->format(self::NS_PRODUCT_DATEFORMAT);
    }

}