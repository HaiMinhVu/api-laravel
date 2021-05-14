<?php

namespace App\Http\Controllers;

use App\Models\NetsuiteProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NetsuiteProductController extends Controller
{
    public function index(Request $request)
	{
        $query = NetsuiteProduct::hasActiveManufacturer();
        if($request->has('select_fields')) {
            $query->filteredSelect($request->select_fields);
        }
		return $query->without('productCategory')->get();
	}

    /**
     * Mass update NetsuiteProducts
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function massUpdate(Request $request)
    {
        return collect($request->all())->map(function($netsuiteProduct){
            return NetsuiteProduct::updateOrCreate(['nsid' => $netsuiteProduct['nsid']], $netsuiteProduct);
            // return NetsuiteProduct::updateFromRemote($netsuiteProduct);
        })->filter()->count();
    }

    public function getAllNSProducts(Request $request)
    {
        $data = NetsuiteProduct::get()->map(function($item){
            return [
                'id' => $item->nsid,
                'sku' => $item->sku,
                'name' => $item->featured_description,
                'category' => $item->ns_product_category
            ];
        });
        return response()->json(['data' => $data]);
    }

}
