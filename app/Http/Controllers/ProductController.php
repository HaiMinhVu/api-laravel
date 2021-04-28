<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{FeaturedProduct, Product, FileManager};
use App\Http\Resources\{
    ProductCollection as ProductCollectionResource,
    ProductWithRelations as ProductWithRelationsResource
};

use App\Http\Resources\ProductWithManual;
use Cache;
use Response;

class ProductController extends Controller
{

    /**
     * @OA\Get(
     *     path="/v1/{$manufacturerId}/products",
     *     tags={"products"},
     *     summary="Returns list of active products associated with a brand",
     *     operationId="index",
     *     security={{ "apiKey":{} }},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/ProductResource")
     *       ),
     *     @OA\Response(
     *         response=400,
     *         description="Cannot find brand slug"
     *     )
     * )
     */
    public function index(Request $request, $manufacturerId)
    {
        $products = Product::active()->byManufacturer($manufacturerId)->with('netsuiteProduct')->get();
        $data = (new ProductCollectionResource($products))->jsonSerialize();
        return response()->json(['data' => $data]);
    }

    /**
     * @OA\Get(
     *     path="/v1/{$manufacturerId}/product/{$nsid}",
     *     tags={"product"},
     *     summary="Get a single product by NetSuite ID",
     *     operationId="show",
     *     security={{ "apiKey":{} }},
     *     @OA\Response(
     *         response=400,
     *         description="Cannot find brand slug or NSID"
     *     )
     * )
     */
    public function show(Request $request, $manufacturerId, $nsid)
    {
        $product = Product::byManufacturer($manufacturerId)->where('nsid', $nsid)->withAllRelations()->first();
        $data = ($product) ? (new ProductWithRelationsResource($product))->jsonSerialize() : null;
        return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function showWithoutManufacturer(Request $request, $nsid)
    {
        $product = Product::where('nsid', $nsid)->withAllRelations()->first();
        $data = ($product) ? (new ProductWithRelationsResource($product))->jsonSerialize() : null;
        return response()->json(['data' => $data], 200, ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function getSlugs(Request $request, $manufacturerId)
    {
        $data = Product::withoutGlobalScopes()->select('nsid', 'feature_name')->get()->map(function($item){
            return [
                'id' => $item->nsid,
                'name' => $item->feature_name
            ];
        });
        return response()->json(['data' => $data]);
    }

    public function getFeaturedList(Request $request)
    {
        $data = FeaturedProduct::select(['id', 'description'])->without('product')->where('pid', 0)->get();
        return response()->json(['data' => $data]);
    }

    public function getFeatured(Request $request, $featuredProduct)
    {
        $featuredProductParent = FeaturedProduct::with(['featuredProducts'])->find($featuredProduct);
        $featuredProducts = $featuredProductParent->featuredProducts->map(function($featuredProduct){
            return $featuredProduct->product;
        })->sortByDesc('id');
        $data = (new ProductCollectionResource($featuredProducts))->jsonSerialize();
        return response()->json(['data' => $data]);
    }

    public function getAllProducts(Request $request)
    {
        $data = Product::withoutGlobalScopes()->select('nsid', 'sku', 'feature_name')->get()->map(function($item){
            return [
                'id' => $item->nsid,
                'sku' => $item->sku,
                'name' => $item->feature_name
            ];
        });
        return response()->json(['data' => $data]);
    }

    public function getProductsWithManual(Request $request)
    {
        $results = Product::with(
            'manuals',
        )->select('id', 'sku', 'nsid', 'Name')->get();

        $items = $results->map(function($item){
            return [
                'sku' => $item->sku,
                'name' => $item->Name,
                'manuals' => $item->manuals->map(function($manual){
                    return new ProductWithManual($manual);
                })
            ];
                 
        });

        $file = fopen("products.csv","w");
        fputcsv($file, array('SKU', 'Name', 'Brand', 'File Name', 'Url', 'Languages'));

        foreach ($items as $item) {
            foreach ($item['manuals'] as $manual) {
                $manual = json_decode(json_encode($manual), 1);
                if(count($manual)){
                    $tmp = [
                        $item['sku'],
                        $item['name'],
                        array_key_exists("brand",$manual) ? $manual['brand'] : '',
                        array_key_exists("file_name",$manual) ? $manual['file_name'] : '',
                        array_key_exists("url",$manual) ? $manual['url'] : '',
                        array_key_exists("languages",$manual) ? $manual['languages'] : ''
                    ];
                    fputcsv($file, $tmp);
                }
                
            }
        }
        return 'Exported';
    }

}