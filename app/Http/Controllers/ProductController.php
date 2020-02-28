<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use App\Models\{FeaturedProduct, Product};
use App\Http\Resources\{
    ProductCollection as ProductCollectionResource,
    ProductWithRelations as ProductWithRelationsResource
};
use Illuminate\Support\Str;
use Cache;

class ProductController extends Controller
{

    public function index(Request $request, $manufacturerId)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerId) { 
            $products = Product::active()->byManufacturer($manufacturerId)->with('netsuiteProduct')->get();
            return (new ProductCollectionResource($products))->jsonSerialize();
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
            return ($product) ? (new ProductWithRelationsResource($product))->jsonSerialize() : null;
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
        return Product::withoutGlobalScopes()->select('nsid', 'feature_name')->get()->map(function($item){
            return [
                'id' => $item->nsid,
                'name' => $item->feature_name
            ];
        });
    }

    public function getFeatured(Request $request, $manufacturerId, $featuredProduct)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerId, $featuredProduct) { 
            $featuredProduct = FeaturedProduct::with(['featuredProducts'])->find($featuredProduct);
            return $featuredProduct;
        });
    }

}
