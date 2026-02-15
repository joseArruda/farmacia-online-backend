<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\CartController;

Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index']);
    Route::get('{id}', [InventoryController::class, 'show']);
    Route::post('/', [InventoryController::class, 'store']);
    Route::put('{id}', [InventoryController::class, 'update']);
    Route::delete('{id}', [InventoryController::class, 'destroy']);
});

Route::prefix('cart')->group(function () {
    Route::post('/', [CartController::class, 'store']);
    Route::get('/', [CartController::class, 'index']);
    Route::delete('{id}', [CartController::class, 'destroy']);
});
