<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{FeaturedProduct, Product};
use App\Http\Resources\{
    ProductCollection as ProductCollectionResource,
    ProductWithRelations as ProductWithRelationsResource
};
use Cache;

class ProductController extends Controller
{

    public function index(Request $request, $manufacturerId)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerId) {
            $products = Product::active()->byManufacturer($manufacturerId)->with('netsuiteProduct')->get();
            $data = (new ProductCollectionResource($products))->jsonSerialize();
            return response()->json(['data' => $data]);
        });
    }

    public function store(Request $request)
    {
        return Product::create($request->all());
    }

    public function show(Request $request, $manufacturerId, $nsid)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerId, $nsid) {
            $product = Product::byManufacturer($manufacturerId)->where('nsid', $nsid)->withAllRelations()->first();
            $data = ($product) ? (new ProductWithRelationsResource($product))->jsonSerialize() : null;
            return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
        });
    }

    public function update(Request $request, $product)
    {
        $product->update($request->all());
    }

    public function delete(Request $request, $product)
    {
        // Maybe add later
    }

    public function getSlugs(Request $request, $manufacturerId)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerId) {
            $data = Product::withoutGlobalScopes()->select('nsid', 'feature_name')->get()->map(function($item){
                return [
                    'id' => $item->nsid,
                    'name' => $item->feature_name
                ];
            });

            return response()->json(['data' => $data]);
        });
    }

    public function getFeaturedList(Request $request)
    {
        return $this->cacheResponse($request, function() use ($request) {
            $data = FeaturedProduct::select(['id', 'description'])->without('product')->where('pid', 0)->get();
            return response()->json(['data' => $data]);
        });
    }

    public function getFeatured(Request $request, $featuredProduct)
    {
        return $this->cacheResponse($request, function() use ($request, $featuredProduct) {
            $featuredProductParent = FeaturedProduct::with(['featuredProducts'])->find($featuredProduct);

            $featuredProducts = $featuredProductParent->featuredProducts->map(function($featuredProduct){
                return $featuredProduct->product;
            })->sortByDesc('id');

            $data = (new ProductCollectionResource($featuredProducts))->jsonSerialize();
            return response()->json(['data' => $data]);
        });
    }

}
