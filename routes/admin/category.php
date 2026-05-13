<?php

use App\Http\Controllers\Admin\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('categories', CategoryController::class)
    ->except(['store', 'update', 'destroy']);

Route::apiResource('categories', CategoryController::class)
    ->only(['store', 'update', 'destroy'])
    ->middleware(['auth:sanctum', 'admin']);