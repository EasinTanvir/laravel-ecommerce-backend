<?php

use App\Http\Controllers\Admin\Products\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class)
    ->except(['store', 'update', 'destroy']);

Route::apiResource('products', ProductController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum', 'admin']);