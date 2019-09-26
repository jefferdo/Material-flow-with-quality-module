<?php

use Illuminate\Routing\Router;

/** @var $router Router */


$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/', ['uses' => 'UsersController@index']);
    $router->post('/', ['uses' => 'UsersController@login']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/matRec', ['uses' => 'UsersController@matRec']);
    $router->post('/matRec', ['uses' => 'UsersController@searchMatRec']);
});

// catch-all route
$router->any('{any}', function () {
    return 'four oh four';
})->where('any', '(.*)');
