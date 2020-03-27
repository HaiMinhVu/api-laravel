<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API V2 Routes
|--------------------------------------------------------------------------
*/

// Route::group(['prefix' => '{manufacturerSlug}'], function() use ($router) {
//     $router->group(['prefix' => 'products'], function() use ($router) {
//         $router->get('/', 'ProductController@index');
//         $router->get('names', 'ProductController@getSlugs');
//     });

//     $router->get('categories', 'CategoryController@index');
//     $router->get('categories/all', 'CategoryController@getAll');
//     $router->group(['prefix' => 'category'], function() use ($router) {
//         $router->group(['prefix' => '/{id}'], function() use ($router) {
//             $router->get('/', 'CategoryController@show');
//             $router->get('products', 'CategoryController@getProducts');
//         });
//     });
// });

// Route::group(['prefix' => 'products'], function() use ($router) {
//     $router->get('featured', 'ProductController@getFeaturedList');
//     $router->get('featured/{featuredProduct}', 'ProductController@getFeatured');
//     $router->post('netsuite', 'NetsuiteProductController@massUpdate');
// });

// Route::apiResource('navigation', 'NavController')->only(['index', 'show']);
// Route::apiResource('brand', 'ManufacturerController')->only(['index']);

// Route::group(['prefix' => 'cms', 'namespace' => 'CMS'], function() use ($router) {
//     Route::apiResource('brand', 'BrandController');
// });

$router->group(['namespace' => 'Form'], function() use ($router) {
    $router->apiResource('form', 'FormController');
    $router->apiResource('form-submission', 'SubmissionController');
});