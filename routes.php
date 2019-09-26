<?php

use Illuminate\Routing\Router;

/** @var $router Router */

$router->get('/', function () {
    return 'hello world!';
});

$router->get('bye', function () {
    
});

$router->group(['namespace' => 'App\Controllers', 'prefix' => 'users'], function (Router $router) {
    $router->get('/', ['name' => 'users.index', 'uses' => 'UsersController@index']);
    $router->post('/', ['name' => 'users.store', 'uses' => 'UsersController@store']);
});

$router->group(['namespace' => 'App\Controllers', 'prefix' => 'matRec'], function (Router $router) {
    $router->get('/', ['name' => 'matRec.index', 'uses' => 'UsersController@matRec']);
    $router->post('/', ['name' => 'matRec.searchMatRec', 'uses' => 'UsersController@searchMatRec']);
});

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
