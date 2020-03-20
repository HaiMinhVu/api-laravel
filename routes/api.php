<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => '{manufacturerId}'], function() use ($router) {
    $router->post('contact', 'ContactUsController');
    $router->group(['prefix' => 'product'], function() use ($router) {
        $router->post('registration', 'ProductRegistrationController');
        $router->get('/{nsid}', 'ProductController@show');
    });

    $router->group(['prefix' => 'products'], function() use ($router) {
        $router->get('/', 'ProductController@index');
        $router->get('names', 'ProductController@getSlugs');
    });

    $router->get('categories', 'CategoryController@index');
    $router->get('categories/all', 'CategoryController@getAll');
    $router->group(['prefix' => 'category'], function() use ($router) {
        $router->group(['prefix' => '/{id}'], function() use ($router) {
            $router->get('/', 'CategoryController@show');
            $router->get('products', 'CategoryController@getProducts');
        });
    });

    // $router->get('slider/{slider}', 'SliderController@show');
});

$router->group(['prefix' => 'products'], function() use ($router) {
    $router->get('featured', 'ProductController@getFeaturedList');
    $router->get('featured/{featuredProduct}', 'ProductController@getFeatured');
    $router->post('netsuite', 'NetsuiteProductController@massUpdate');
});

// Route::resource('script', 'ScriptController')->only(['index', 'show']);
Route::get('script/{url}', 'ScriptController@getUrl');
Route::get('item/status/{id}', 'ScriptController@getStatus');

$router->group(['prefix' => 'external', 'middleware' => ['cachable.resource']], function() use ($router)  {
    $router->get('cache', 'ScriptController@getExternalUrlFromParam');
    $router->get('cache/{encodedUrl}', 'ScriptController@getExternalUrl');
});

Route::resource('slider', 'SliderController')->only(['index', 'show']);
// Route::get('dealers', 'DealerController@index');
Route::apiResource('navigation', 'NavController')->only(['index', 'show']);
Route::apiResource('brand', 'ManufacturerController')->only(['index']);