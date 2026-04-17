<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

Route::middleware(['throttle:60,1'])
    ->group(function () {
        Route::apiResource('inventory', InventoryController::class)
        ->whereNumber('inventory');
});

Route::middleware(['throttle:60,1'])
    ->prefix('cart')
    ->whereNumber('id')
    ->group(function () {
        Route::post('/', [CartController::class, 'store']);
        Route::put('/{id}', [CartController::class, 'update']);
        Route::get('/', [CartController::class, 'index']);
        Route::delete('{id}', [CartController::class, 'destroy']);
});