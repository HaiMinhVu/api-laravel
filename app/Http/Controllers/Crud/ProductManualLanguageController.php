<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Product,
    FileManager,
    Language,
    Manual
};

class ProductManualLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product, FileManager $manual)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, FileManager $manual)
    {
        if($request->has('ids')) {
            $ids = $request->ids;
            return $manual->manuals->map(function($manual) use ($ids) {
                return $manual->languages()->sync($ids);
            });
        }
    }
}
