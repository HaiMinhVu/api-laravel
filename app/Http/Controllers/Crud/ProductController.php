<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\{
    FileManager,
    Product
};
use Illuminate\Http\Request;
use App\Http\Resources\Crud\ProductListItem as ProductListItemResource;
use S3;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, FileManager $fileManager)
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

    public function getList(Request $request, int $width = 40)
    {
        return Product::orderBy('id', 'desc')->get()->map(function($product) use ($width) {
            return new ProductListItemResource($product, $width);
            $s3FilePath = $product->mainImage->s3FilePath();
            $height = $width;

            $imageUrl = S3::imageLink($s3FilePath, $width, [
                'height' => $height,
                'background' => [
                    'r' => 255,
                    'g' => 255,
                    'b' => 255,
                    'alpha' => 1
                ]
            ]);

            return [
                'id' => $product->id,
                'nsid' => $product->nsid,
                'name' => $product->Name,
                'sku' => $product->sku,
                'image' => $imageUrl
            ];
        })->filter();
    }
}
