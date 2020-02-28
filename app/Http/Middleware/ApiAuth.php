<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class ApiAuth
{
    const CODE = 401;
    const MESSAGE = 'Unauthorized';
    const HEADER_KEY = 'X-API-KEY';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->authorized($request)) {
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
        return $request->header(self::HEADER_KEY) === config('auth.api_auth.token');
    }
}
