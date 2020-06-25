<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API V1 Crud Routes
|--------------------------------------------------------------------------
*/

$router->group(['namespace' => 'Crud'], function() use ($router) {
    $router->apiResource('featured-product', 'FeaturedProductController');
    $router->get('product/list/{width?}', 'ProductController@getList')->name('product.list');
    $router->apiResource('product', 'ProductController');
    $router->apiResource('file', 'FileController');
    $router->apiResource('product.image', 'ProductImageController')->only(['index', 'store', 'show']);
    $router->apiResource('product.reticle', 'ProductReticleController')->only(['index', 'store', 'show']);
    $router->apiResource('brand', 'BrandController')->only('index');
    $router->apiResource('category', 'CategoryController');
    // $router->apiResource('product.file', 'ProductFileController');
});
