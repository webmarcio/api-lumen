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



$router->group(['prefix' => 'api'], function() use($router) {
    $router->group(['prefix' => 'user'], function () use($router) {
        $router->post('/', 'UserController@create');
        $router->get('{id}', 'UserController@getUser');
        $router->put('{id}', 'UserController@update');
        $router->delete('{id}', 'UserController@delete');
    });
    $router->get('users', 'UserController@getAllUsers');

    $router->post('login', 'UserController@login');
    $router->post('info', 'UserController@viewUserAuth');
    $router->post('logout', 'UserController@logout');
});



