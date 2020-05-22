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
});