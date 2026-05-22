<?php

use App\Http\Controllers\Admin\Orders\OrderController;
use Illuminate\Support\Facades\Route;

Route::apiResource('orders', OrderController::class)
    ->except(['store', 'update', 'destroy']);

Route::apiResource('orders', OrderController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum', 'admin']);