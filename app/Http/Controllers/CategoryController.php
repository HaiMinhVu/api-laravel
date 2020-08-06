<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use App\Models\ProductCategory;
use App\Http\Resources\{
    ProductCollection as ProductCollectionResource,
    ProductCategory as ProductCategoryResource,
    ProductCategoryCollection as CategoryCollectionResource
};
use Cache;
use Str;

class CategoryController extends Controller
{

    public function index(Request $request, $manufacturerSlug)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerSlug) {
            $productCategories = ProductCategory::topLevel()->byManufacturer($manufacturerSlug)->hasActiveProducts()->get();

            $data = (new CategoryCollectionResource($productCategories))->jsonSerialize();
            return response()->json(['data' => $data]);
        });
    }

    public function show(Request $request, $manufacturerSlug, $id)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerSlug, $id) {
            $category = ProductCategory::where('id', $id)->with(['subCategories' => function($q){
                $q->hasActiveProducts();
                $q->with('fileManager');
            }])->first();

            $data = (new ProductCategoryResource($category))->jsonSerialize();
            return response()->json(['data' => $data]);
        });
    }

    public function getProducts(Request $request, $manufacturerSlug, $id)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerSlug, $id) {
            $products = ProductCategory::find($id)->products()->where(function($q){
                $q->active();
            })->get();
            return (new ProductCollectionResource($products));
        });
    }

    public function getAll(Request $request, $manufacturerSlug)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerSlug) {
            $categories = ProductCategory::where(function($q) use ($manufacturerSlug){
                $q->where(function($q) use ($manufacturerSlug){
                    $q->byManufacturer($manufacturerSlug);
                })->orWhere(function($q) use ($manufacturerSlug){
                    $q->topLevel()->byManufacturer($manufacturerSlug);
                })->hasParent();
            })->get();

            $data = new CategoryAllCollectionResource($categories);

            return response()->json(['data' => $data]);
        });
    }

}
