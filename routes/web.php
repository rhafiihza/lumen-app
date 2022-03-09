<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

//auth
$router->post('/login', ['uses' => 'AuthController@login']);
$router->post('/register', ['uses' => 'AuthController@register']);
$router->post('/logout', ['uses' => 'AuthController@logout']);

//products
$router->post('/products', ['middleware' => 'auth', 'uses' => 'ProductController@store']); //create product
$router->get('/products', ['middleware' => 'auth', 'uses' => 'ProductController@index']); //get all products
$router->get('/products/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@show']);   //get product by id
$router->put('/products/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@update']); //update product by id
$router->delete('/products/{id}', ['middleware' => 'auth', 'uses' => 'ProductController@destroy']); //delete product by id

//transactions
$router->post('/transactions', ['middleware' => 'auth', 'uses' => 'TransactionController@store']); //create transaction
