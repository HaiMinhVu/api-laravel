<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderImage;
use App\Http\Resources\SliderImageCollection as SliderImageCollectionResource;

class SliderController extends Controller
{
    public function show(Request $request, $manufacturer, $id)
    {
        $images = SliderImage::where('pid', $id)->get();
        return new SliderImageCollectionResource($images);
    }
}
