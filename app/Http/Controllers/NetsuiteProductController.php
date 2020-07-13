<?php

namespace App\Http\Controllers;

use App\Models\NetsuiteProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NetsuiteProductController extends Controller
{
    public function index(Request $request)
	{
        $query = NetsuiteProduct::query();
        if($request->has('fields')) {
            $query->filteredSelect($request->fields);
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
            return NetsuiteProduct::updateFromRemote($netsuiteProduct);
        })->filter()->count();
    }

}
