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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, FileManager $manual)
    {
        // return $manual;
        // if($manual->ID == 15313) {
            // dd($manual->manuals()->map(function($manual){
            //     $manualId
            // })->sync($request->ids));
        // }
        // return FileManager::find($manualId)->manuals()->languages()->sync($request->ids);
        // Poor relational structure requires weird functionality here
        // if($manual->ID == 495) {
            // $test = Manual::orderBy('id', 'DESC')->first();
            // dd($test);
            // dd($manual->manuals);
        // }
        // if($request->has('ids')) {
            $ids = $request->ids;
            return $manual->manuals->map(function($manual) use ($ids) {
                // $manual->languages()->delete();
                // return $manual->id;
                return $manual->languages()->sync($ids);
                // dd([$manual->languages, $ids]);
                // return $manual->languages;
                // dd($manual->load('languages'));
                // return $manual->languages()->get();
            });
        // }
        // dd([
        //     $product,
        //     $manual,
        //     $request->all()
        // ]);
    }
}
