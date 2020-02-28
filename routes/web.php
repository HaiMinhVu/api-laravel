<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


$router->group(['prefix' => '{manufacturerId}'], function() use ($router) {


    $router->post('contact', 'ContactUsController');
    $router->group(['prefix' => 'product'], function() use ($router) {
        $router->post('registration', 'ProductRegistrationController');
        $router->get('/{nsid}', 'ProductController@show');
    });

    $router->group(['prefix' => 'products'], function() use ($router) {
        $router->get('/', 'ProductController@index');
        $router->get('names', 'ProductController@getSlugs');
        $router->get('featured/{featuredProduct}', 'ProductController@getFeatured');
    });

    $router->get('categories', 'CategoryController@index');
    $router->get('categories/all', 'CategoryController@getAll');
    $router->group(['prefix' => 'category'], function() use ($router) {
        $router->group(['prefix' => '/{id}'], function() use ($router) {
            $router->get('/', 'CategoryController@show');
            $router->get('products', 'CategoryController@getProducts');
        });
    });

    $router->get('slider/{slider}', 'SliderController@show');
});

$router->get('dealers', 'DealerController@index');

$router->get('navigation/{id}', 'NavController@show');


// $router->get("{any}", function(){
//     abort(404);
// })->where('any', '.*');


