<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\{
    FeaturedProduct,
    Product
};
use Illuminate\Http\Request;
use App\Http\Resources\Crud\FeaturedProduct as FeaturedProductResource;

class FeaturedProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FeaturedProduct::isParent()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $featuredProduct = FeaturedProduct::create([
            'description' => $request->description
        ]);

        $featuredProduct->associateProductsBySkus($request->skus);
        // foreach($request->skus as $sku) {
        //     if($product = Product::withoutGlobalScopes()->where('sku', $sku)->first()) {
        //         $item = $featuredProduct->children()->create([]);
        //         $item->product()->associate($product);
        //         $item->save();
        //     }
        // }

        return $featuredProduct->load('children.productWithoutGlobalScopes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FeaturedProduct  $featuredProduct
     * @return \Illuminate\Http\Response
     */
    public function show(FeaturedProduct $featuredProduct)
    {
        return new FeaturedProductResource($featuredProduct->load('children.productWithoutGlobalScopes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FeaturedProduct  $featuredProduct
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FeaturedProduct $featuredProduct)
    {
        if($request->has('skus')) {
            $featuredProduct->description = $request->description;
            $featuredProduct->children()->delete();
            $featuredProduct->associateProductsBySkus($request->skus);
            $featuredProduct->save();
        }

        return $featuredProduct->load('children.productWithoutGlobalScopes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FeaturedProduct  $featuredProduct
     * @return \Illuminate\Http\Response
     */
    public function destroy(FeaturedProduct $featuredProduct)
    {
        //
    }

}
