<?php

use App\Http\Controllers\Admin\Orders\OrderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/orders/{order}', [OrderController::class, 'show']);

});


Route::middleware(['auth:sanctum', 'admin'])->group(function () {

    Route::get('/orders', [OrderController::class, 'index']);

    Route::patch('/orders/{order}', [OrderController::class, 'update']);

    Route::delete('/orders/{order}', [OrderController::class, 'destroy']);

});