<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as DB;
use App\Models\ProductCategory;
use App\Http\Resources\{
    ProductCollection as ProductCollectionResource,
    ProductCategory as ProductCategoryResource,
    ProductCategoryCollection as CategoryCollectionResource,
};
use Cache;
use Str;

class CategoryController extends Controller
{

    public function index(Request $request, $manufacturerId)
    {
        return $this->cacheResponse($request, function() use ($request, $manufacturerId) { 
            $ProductCategories = ProductCategory::topLevelCategoriesByManufacturer($manufacturerId)->whereHas('products')->get();
            return (new CategoryCollectionResource($ProductCategories))->jsonSerialize();
        });
    }

    public function store(Request $request)
    {
        return Product::create($request->all());
    }

    public function show(Request $request, $id)
    {
        $category = ProductCategory::where('id', $id)->with(['subCategories' => function($q){
            $q->whereHas('products');
            $q->with('fileManager');
        }])->first();

        return new ProductCategoryResource($category);

        $category['sub_categories'] = new CategoryCollectionResource($category['sub_categories']);
        return $category;
    }

    public function update(Request $request, $product)
    {
        $product->update($request->all());
    }

    public function delete(Request $request, $product)
    {
        // Maybe add later
    }

    public function getProducts(Request $request, $manufacturerId, $id)
    {
        $products = ProductCategory::find($id)->products()->where(function($q){
            $q->active();
        })->get();
        return (new ProductCollectionResource($products));
    }

    public function getAll(Request $request, $manufacturerId)
    {
        $categories = ProductCategory::where(function($q) use ($manufacturerId){
            $q->where(function($q) use ($manufacturerId){
                $q->byManufacturer($manufacturerId);
            })->orWhere(function($q) use ($manufacturerId){
                $q->topLevelCategoriesByManufacturer($manufacturerId);
            })->hasParent();
        })->get();
        // dd($cate)
        // $categories = ProductCategory::byManufacturer($manufacturerId)->get();
        return new CategoryCollectionResource($categories);
    }

}
