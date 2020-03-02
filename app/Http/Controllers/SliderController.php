<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SliderImage;
use App\Http\Resources\SliderImageCollection as SliderImageCollectionResource;

class SliderController extends Controller
{
	public function index(Request $request)
	{
		return $this->cacheResponse($request, function() {
			$data = SliderImage::whereNotNull('description')
				   ->where('pid', 0)
				   ->whereHas('images')
				   ->without(['images', 'fileManager'])
				   ->select(['id', 'description'])
				   ->orderBy('description')
				   ->get();
			return response()->json(['data' => $data]);
		});
	}

    public function show(Request $request, $id)
    {
    	return $this->cacheResponse($request, function() use ($request, $id) { 
	        $images = SliderImage::where('pid', $id)->get();
	        $data = new SliderImageCollectionResource($images);
	        return response()->json(['data' => $data]);
	    });
    }
}
