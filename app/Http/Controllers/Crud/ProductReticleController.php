<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use App\Models\{ FileManager, Product };
use Illuminate\Http\Request;
use App\Http\Resources\Crud\FileListItem;

class ProductReticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $productId)
    {
        $product = Product::withoutGlobalScopes()->with(['reticles'])->find($productId);
        
        $reticles = $product->reticles->filter(function($reticle){
            return $reticle->fileManager()->exists();
        })->map(function($reticle){
            return new FileListItem($reticle->fileManager);
        })->values();

        return response()->json(['reticles' => $reticles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $productId)
    {
        $product = Product::withoutGlobalScopes()->with(['reticles'])->find($productId);

        $product->reticles()->delete();

        if($request->has('reticles') && is_array($request->reticles)) {
            $reticleIds = collect($request->reticles)->filter(function($reticle) {
                return array_key_exists('id', $reticle);
            })->map(function($reticle) use ($product) {
                return $reticle['id'];
            })->all();
            $product->syncReticles($reticleIds);
        }

        if($product->isDirty()) {
            $product->save();
        }

        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $product->load('reticles');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
