<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;

// Route::get('/', function () {
//     return view('shop.index');
// });

Route::get('/', [StoreController::class, 'index']);

Route::get('/products', [StoreController::class, 'products']);

Route::get('/productDetails', [StoreController::class, 'productDetails']);

Route::get('/cart', [StoreController::class, 'cart']);

Route::get('/aboutUs', [StoreController::class, 'aboutUs']);

Route::get('/contact', [StoreController::class, 'contact']);
