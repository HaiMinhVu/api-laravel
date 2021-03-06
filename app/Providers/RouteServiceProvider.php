<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiCrudRoutes();
        $this->mapApiRoutes();
        $this->mapWebRoutes();
        $this->mapApiV2Routes();
        $this->mapFileRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('v1')
             // ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * Define the "crud" v1 routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiCrudRoutes()
    {
        Route::prefix('v1/crud')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/crud.php'));
    }

    /**
     * Define the "api" v2 routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiV2Routes()
    {
        Route::prefix('v2')
             // ->middleware('api')
             ->namespace("{$this->namespace}\V2")
             ->group(base_path('routes/api-v2.php'));
    }

    /**
     * Define the "api" v2 routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapFileRoutes()
    {
        Route::prefix('file')
             ->middleware('file')
             ->namespace($this->namespace)
             ->group(base_path('routes/file.php'));
    }

}
