<?php

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

$router->post('api/user', 'UserController@create');
$router->get('api/user/{id}', 'UserController@getUser');
$router->get('api/users', 'UserController@getAllUsers');
$router->put('api/user/{id}', 'UserController@update');
$router->delete('api/user/{id}', 'UserController@delete');

