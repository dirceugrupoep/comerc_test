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

$router->post('/login', 'AuthController@login');

// Rotas protegidas por JWT
$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/profile', function () {
        return auth()->user();
    });
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/clients', 'ClientController@index');
    $router->get('/clients/{id}', 'ClientController@show');
    $router->post('/clients', 'ClientController@store');
    $router->put('/clients/{id}', 'ClientController@update');
    $router->delete('/clients/{id}', 'ClientController@destroy');
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/products', 'ProductController@index');
    $router->get('/products/{id}', 'ProductController@show');
    $router->post('/products', 'ProductController@store');
    $router->put('/products/{id}', 'ProductController@update');
    $router->delete('/products/{id}', 'ProductController@destroy');
});

$router->group(['middleware' => 'jwt.auth'], function () use ($router) {
    $router->get('/orders', 'OrderController@index');
    $router->get('/orders/{id}', 'OrderController@show');
    $router->post('/orders', 'OrderController@store');
    $router->put('/orders/{id}', 'OrderController@update');
    $router->delete('/orders/{id}', 'OrderController@destroy');
});
