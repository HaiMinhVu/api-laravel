<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Router;
use Illuminate\Routing\Route;
use Illuminate\Http\Request;
use App\Models\{
    FeaturedProduct, 
    Product, 
    ProductCategory, 
    SliderImage
};
use App\Jobs\CacheRoute;

class CacheRoutes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:api-routes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all api routes';

    private $router;
    private $headers;
    private $currentManufacturer;
    private $currentApiVersion;

    const API_VERSIONS = [
        'v1'
    ];

    const MANUFACTURERS = [
        'pulsar',
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Router $router)
    {
        parent::__construct();
        $this->router = $router;
        $this->headers = ['X-Api-Key' => config('auth.api_auth.token')];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach(self::API_VERSIONS as $version) {
            $this->setApiVersion($version);
            foreach(self::MANUFACTURERS as $manufacturer) {
                $this->setManufacturer($manufacturer);
                $this->cacheProductRoutes();
                $this->cacheCategoryRoutes();
            }
            $this->cacheFeaturedRoutes();
            $this->cacheSliderRoutes();
        }
    }

    private function cacheCategoryRoutes()
    {
        $this->cacheAllCategoryRoutes();
        $this->cacheRoute('categories');
    }

    private function cacheProductRoutes()
    {
        $this->cacheAllProductRoutes();
        $this->cacheRoute('products');
    }

    private function cacheFeaturedRoutes()
    {
        $this->cacheAllFeaturedRoutes();
        $this->cacheRoute('products/featured', false);
    }

    private function cacheSliderRoutes()
    {
        $this->cacheAllSliderRoutes();
        $this->cacheRoute('slider', false);
    }

    private function cacheAllCategoryRoutes()
    {
        ProductCategory::byManufacturer('pulsar')->select('id')->get()->map(function($category){
            $this->cacheRoute("category/{$category->id}");
            $this->cacheRoute("category/{$category->id}/products");
        });
    }

    private function cacheAllProductRoutes()
    {
        Product::active()->byManufacturer($this->currentManufacturer)->orderBy('id', 'DESC')->get()->map(function($product){
            $this->cacheRoute("product/{$product->nsid}");
        });
    }

    private function cacheAllFeaturedRoutes()
    {
        FeaturedProduct::select(['id'])->without('product')->where('pid', 0)->get()->map(function($featured){
            $this->cacheRoute("products/featured/{$featured->id}", false);
        });
    }

    private function cacheAllSliderRoutes()
    {
        SliderImage::select('id')->isParent()->get()->map(function($sliderImage){
            $this->cacheRoute("slider/{$sliderImage->id}", false);
        });
    }

    private function cacheRoute($urlPath, $isManufacturerRoute = true)
    {   
        $url = $this->fullRoute($urlPath, $isManufacturerRoute);
        CacheRoute::dispatch($url)->onQueue($this->currentQueue());
        $this->info("Added to queue: {$url}");
    }

    private function fullRoute($urlPath, $isManufacturerRoute = true)
    {
        $route = $this->currentApiVersion;
        if($isManufacturerRoute) {
            $route = "{$route}/{$this->currentManufacturer}";
        }
        return "{$route}/{$urlPath}";
    }

    private function setApiVersion($version)
    {
        $this->currentApiVersion = $version;
    }

    private function setManufacturer($manufacturer)
    {
        $this->currentManufacturer = $manufacturer;
    }

    private function currentQueue()
    {
        return "route_queue";
    }
}
