<?php

namespace App\Http\Middleware;

use Closure;

class CanCacheExternalResource
{
    const ALLOWABLE_HOSTS = [
        'sellmarkcorp.com'
    ];

    protected $host;
    protected $request;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->request = $request;

        if(!$this->isAllowableHost()) {
            return response('Cannot cache external resource', 401);
        }

        return $next($request);
    }

    /**
     * Get encoded url from request.
     *
     * @return bool
     */
    protected function getEncodedUrl() : string
    {
        return $this->request->route('encodedUrl') ?? $this->request->get('url');
    }

    /**
     * Check if hostname is allowed.
     *
     * @return void
     */
    protected function setHost() : void
    {
        try {
            $encodedUrl = $this->getEncodedUrl();
            $decoded = base64_decode($encodedUrl);
            $url = parse_url($decoded);
            $this->host = $url['host'];
        } catch(\Exception $e) {
            // 
        }
    }

    /**
     * Check if hostname is allowed.
     *
     * @return bool
     */
    protected function isAllowableHost() : bool
    {
        $this->setHost();
        return in_array($this->host, self::ALLOWABLE_HOSTS);
    }
}
