<?php

use Illuminate\Routing\Router;

/** @var Router $router */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$router->group(['prefix' => 'todo'], function(Router $router) {
    $router->get('/', 'TodoItemsController@list')->name('todo.items.list');
    $router->get('/{id}', 'TodoItemsController@show')->name('todo.items.show');
    $router->post('/', 'TodoItemsController@add')->name('todo.items.add');
    $router->put('/{id}', 'TodoItemsController@complete')->name('todo.items.complete');
});
