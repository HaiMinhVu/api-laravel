<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\Brand as BrandResource;
use App\Models\{
    Manufacturer,
    SiteList
};

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Legacy database has a lot of unnecessary fields and redundant, inconsistent relations,
     * data associated with brands are related to other resources through the list_manufacture (Manufacturer::class)
     * and site_list (SiteList::class) tables. For this reason, data is pulled from both of these tables
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = SiteList::whereHas('manufacturer')->get();
        $data = BrandResource::collection($sites);
        return response()->json(['data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * Legacy database has a lot of unnecessary fields and redundant, inconsistent relations,
     * this endpoint saves data in this manner to account for that, also website data is now
     * decoupled from urls so url fields are unnecessary
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // First create a new SiteList model entry
        $siteList = new SiteList;
        $siteList->label = $request->name;
        $siteList->save();

        $manufacturer = new Manufacturer;
        $manufacturer->prefix = $siteList->prefix;
        $manufacturer->slug = $siteList->prefix;
        $manufacturer->name = $siteList->label;
        $manufacturer->manufacture_active = 1;
        $manufacturer->save();

        $data = new BrandResource($siteList);
        return response()->json(['data' => $data]);
    }

    public function toggleDelete(Request $request, SiteList $siteList)
    {
        $siteList->manufacturer->toggleDelete();
        $data = new BrandResource($siteList);
        return response()->json(['data' => $data]);
    }

}
