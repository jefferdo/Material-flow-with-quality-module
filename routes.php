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
    $router->get('/preview', ['uses' => 'UsersController@preview']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qa', ['uses' => 'UsersController@qa']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qa_wo', ['uses' => 'UsersController@qa_wo']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qa_items', ['uses' => 'UsersController@qa_items']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qa_itemsF', ['uses' => 'UsersController@qa_itemsF']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaA', ['uses' => 'UsersController@qaA']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaAF', ['uses' => 'UsersController@qaAF']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaAi', ['uses' => 'UsersController@qaAi']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaAif', ['uses' => 'UsersController@qaAif']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/qaR', ['uses' => 'UsersController@qaR']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/logout', ['uses' => 'UsersController@logout']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/getPO', ['uses' => 'UsersController@getPO']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/getWO', ['uses' => 'UsersController@getWO']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/makeWO', ['uses' => 'UsersController@makeWO']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/readyWO', ['uses' => 'UsersController@readyWO']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/sendForWo', ['uses' => 'UsersController@sendForWo']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/sendForWa', ['uses' => 'UsersController@sendForWa']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addDateWo', ['uses' => 'UsersController@addDateWo']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addDateWa', ['uses' => 'UsersController@addDateWa']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addMat', ['uses' => 'UsersController@addMat']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addRoll', ['uses' => 'UsersController@addRoll']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addRollB', ['uses' => 'UsersController@addRollB']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/inMat', ['uses' => 'UsersController@inMat']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/creWF', ['uses' => 'UsersController@creWF']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addWF', ['uses' => 'UsersController@addWF']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/buildWF', ['uses' => 'UsersController@buildWF']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addRollWF', ['uses' => 'UsersController@addRollWF']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/getRoll', ['uses' => 'UsersController@getRoll']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->post('/addNewPO', ['uses' => 'UsersController@addNewPO']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/getSW', ['uses' => 'UsersController@getSW']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/NewGP', ['uses' => 'UsersController@NewGP']);
});

$router->group(['namespace' => 'App\Controllers'], function (Router $router) {
    $router->get('/getBarcode/{key}', ['uses' => 'UsersController@getBarcode']);
});

// catch-all route
$router->any('{any}', function () {
    $blade = new BladeOne($views, $cache, BladeOne::MODE_AUTO);
    echo $blade->run("404");
})->where('any', '(.*)');
