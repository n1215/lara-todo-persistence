<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use N1215\LaraTodo\Http\Controllers\TodoItemsController;

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

Route::pattern('id', '[0-9]+');

Route::group(['prefix' => 'todo'], function (Router $router) {
    $router->get('/', [TodoItemsController::class, 'list'])->name('todo.items.list');
    $router->get('/{id}', [TodoItemsController::class, 'show'])->name('todo.items.show');
    $router->post('/', [TodoItemsController::class, 'add'])->name('todo.items.add');
    $router->put('/{id}', [TodoItemsController::class, 'complete'])->name('todo.items.complete');
});
