<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Manufacturer,
    SiteList
};

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = SiteList::whereHas('files')->get()->map(function($site) {
            $manufacturer = optional($site->manufacturer);
            return [
                'name' => $site->label,
                'prefix' => $site->prefix,
                'url' => $site->url,
                'brand_id' => $manufacturer->id,
                'brand_name' => $manufacturer->name
            ];
        });
        return response()->json(['data' => $sites]);
    }
}
