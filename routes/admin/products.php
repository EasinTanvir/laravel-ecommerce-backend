<?php

use App\Http\Controllers\Admin\Products\ProductController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','admin'])->group(function(){

Route::apiResource('products',ProductController::class);

});