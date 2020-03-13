<?php

namespace App\Http\Controllers;

use App\Models\NetsuiteProduct;
use Illuminate\Http\Request;
use Carbon\Carbon;

class NetsuiteProductController extends Controller
{
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