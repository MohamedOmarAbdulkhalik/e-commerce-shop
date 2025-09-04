<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;

// Route::get('/', function () {
//     return view('shop.index');
// });

Route::get('/', [StoreController::class, 'index']);

Route::get('/products', [StoreController::class, 'products']);

