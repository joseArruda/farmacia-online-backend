<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

Route::get('/inventory', [InventoryController::class, 'index']);
Route::get('/cart', [CartController::class, 'index']);
Route::get('/inventory/{id}', [InventoryController::class, 'show']);

Route::post('/inventory', [InventoryController::class, 'store']);
Route::post('/cart/add', [CartController::class, 'add']);

Route::post('/inventory/{id}', [InventoryController::class, 'update']);
Route::post('/inventory/{id}', [InventoryController::class, 'edit']);

Route::delete('/inventory/{id}', [InventoryController::class, 'destroy']);
Route::delete('/cart/remove/{id}', [CartController::class, 'destroy']);
