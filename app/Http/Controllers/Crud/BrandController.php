<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteList;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sites = SiteList::whereHas('files')->get()->map(function($site){
            return [
                'name' => $site->label,
                'prefix' => $site->prefix,
                'url' => $site->url
            ];
        });
        return response()->json(['data' => $sites]);
    }
}
