<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use App;

class FilterOrigin
{
    const CODE = 401;
    const MESSAGE = 'Unauthorized';
    CONST ALLOWABLE_HOSTS = [
        'https://cms.slmk.dev'
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!$this->authorized($request)) {
            return response()->json(['message' => self::MESSAGE], self::CODE);
        }
        return $next($request);
    }

    /**
     * Check if request is authorized
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    private function authorized(Request $request)
    {
        if(!App::environment('local')) {
            return in_array($request->server->get('HTTP_ORIGIN'), self::ALLOWABLE_HOSTS);
        }
        return true;
    }

}
