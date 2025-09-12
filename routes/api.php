<?php

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\CategoryController;

Route::prefix('v1')->group(function () {
    // Public routes
    Route::apiResource('products', ProductController::class)->only(['index', 'show']);
    Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
    
    // Protected routes (require authentication)
    // Route::middleware('auth:sanctum')->group(function () {
    //     Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    //     Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    // });
});