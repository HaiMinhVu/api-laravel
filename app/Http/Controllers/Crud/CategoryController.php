<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Manufacturer,
    ProductCategory
};
use App\Http\Resources\Crud\{
    Category as CategoryResource,
    ProductCategoryCollection as ProductCategoryResourceCollection
};

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Recursively load several levels of subCategories to minimize initial queries
        $categories = ProductCategory::with('subCategories.subCategories.subCategories.subCategories.subCategories')->isParent()->get();
        $categories = new ProductCategoryResourceCollection($categories);
        return response()->json(['data' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return ProductCategory::create($this->mapRequest($request));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductCategory $category)
    {
        $category->update($this->mapRequest($request));
        return $category;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function show(ProductCategory $category)
    {
        return response()->json(['data' => new CategoryResource($category)]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductCategory  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductCategory $category)
    {
        return response()->json(['data' => ['deleted' => $category->delete()]]);
    }

    private function mapRequest(Request $request) {
        return [
            'label' => $request->name,
            'parent' => ($request->parent_id ?? 0),
            'thumbnail' => $request->thumbnail_id,
            'manufacture' => $request->brand_id,
            'short_description' => $request->short_description,
            'long_description' => $request->long_description,
            'pc_text' => $request->text
        ];
    }

}
