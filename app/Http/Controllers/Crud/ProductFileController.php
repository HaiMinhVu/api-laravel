<?php

namespace App\Http\Controllers\Crud;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    FileManager,
    Product
};
use App\Http\Resources\Crud\FileListItem;

class ProductFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Product $product)
    {
        $files = $product->files();
        if($request->has('type')) {
            $type = $request->type;
            $files = $product->files()->filter(function($file) use ($type) {
                return $file->isType($type);
            });
        }
        return FileListItem::collection($files);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'ids' => 'array'
        ]);
        if($validated) {
            $product->syncFilesByType($request->type, $request->ids);
        }
    }
}
