<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class CacheRoute implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $route;
    private $headers;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($route)
    {
        $this->route = $route;
        $this->headers = ['X-Api-Key' => config('auth.api_auth.token')];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cache($this->route);
    }

    private function cache($route)
    {   
        try {
            $url = $this->route;
            $req = Request::create("{$url}/?force-update=1", 'GET');
            $req->headers->add($this->headers);
            $res = app()->handle($req);
            $data = $res->getContent();
        } catch(\Exception $e) {
            // TODO: log error
        }
    }
}
