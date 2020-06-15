<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\V2\FileType;

class RestrictViewableFileTypes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($key = $request->route()->parameter('key')) {
            $pathInfo = pathinfo($key);
            [$brandSlug, $typePath] = explode('/', $pathInfo['dirname']);
            $isRestricted = FileType::isPathRestricted($typePath);
            if($isRestricted) return abort(403);
        }

        return $next($request);
    }

}
