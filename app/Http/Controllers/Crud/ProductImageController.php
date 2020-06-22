<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\{ FileManager, Product };
use Illuminate\Http\Request;
use App\Http\Resources\Crud\FileListItem;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $productId)
    {
        $product = Product::withoutGlobalScopes()->with(['images.fileManager', 'mainImage'])->find($productId);

        $fileManagerModels = $product->images->filter(function($image){
            return $image->fileManager()->exists();
        })->map(function($image){
            return new FileListItem($image->fileManager);
        })->values();

        $mainImage = ($product->mainImage()->exists() && !is_null($product->mainImage)) ? new FileListItem($product->mainImage) : null;

        return response()->json([
            'images' => $fileManagerModels,
            'main_image' => $mainImage
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $productId)
    {
        $product = Product::withoutGlobalScopes()->with(['images.fileManager', 'mainImage'])->find($productId);

        if($request->has('main_image')) {
            $product->mainImage()->delete();
            if($request->main_image && array_key_exists('id', $request->main_image)) {
                $product->main_img_id = $request->main_image['id'];
            }
        }

        $product->images()->delete();

        if($request->has('images') && is_array($request->images)) {
            $imageIds = collect($request->images)->filter(function($image) {
                return array_key_exists('id', $image);
            })->map(function($image) use ($product) {
                return $image['id'];
            })->all();
            $product->syncImages($imageIds);

            // If main image not assigned, make first image the main image
            if(count($imageIds) > 0 && !$product->mainImage()->exists()) {
                $product->main_img_id = $imageIds[0];
            }
        }

        if($product->isDirty()) {
            $product->save();
        }

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product->load('images.fileManager');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        //
    }
}
