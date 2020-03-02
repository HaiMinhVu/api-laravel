<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NavCat;

class NavController extends Controller
{
	public function index(Request $request)
	{
		return $this->cacheResponse($request, function() use ($request) { 
			$data = NavCat::isParent()->select(['id', 'label'])->get();
			return response()->json(['data' => $data]);
		});
	}

    public function show(Request $request, $id)
    {
		return $this->cacheResponse($request, function() use ($request, $id) { 
	        $data = optional(NavCat::find($id))->items;
	        return response()->json(['data' => $data]);
	    });
    }
}
