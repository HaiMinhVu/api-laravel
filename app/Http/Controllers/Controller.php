<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Cache;
use Closure;
use Str;
use App;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // protected function cacheResponse(Request $request, Closure $callback) {
    // 	$cacheKey = Str::slug($request->path()).'_'.App::environment();

    // 	if(!Cache::has($cacheKey) || $request->has('force-update')) {
    // 		Cache::put($cacheKey, $callback()); 
    // 	}

    // 	return Cache::get($cacheKey);
    // }
}
