<?php

use eftec\bladeone\BladeOne;
use Illuminate\Routing\Router;

/** @var $router Router */

$views = $_SERVER['DOCUMENT_ROOT'] . '/views';
$cache = $_SERVER['DOCUMENT_ROOT'] . '/cache';

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/', ['uses' => 'UsersController@index']);
    $router->post('/login', ['uses' => 'UsersController@login']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/matRec', ['uses' => 'UsersController@matRec']);
    $router->post('/matRec', ['uses' => 'UsersController@searchMatRec']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/preview', ['uses' => 'UsersController@preview']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qa', ['uses' => 'UsersController@qa']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaA', ['uses' => 'UsersController@qaA']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaR', ['uses' => 'UsersController@qaR']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/logout', ['uses' => 'UsersController@logout']);
});
// catch-all route
$router->any('{any}', function () {
    $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
    echo $blade->run("404");
})->where('any', '(.*)');
