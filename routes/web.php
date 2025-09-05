<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;

// Route::get('/', function () {
//     return view('shop.index');
// });

Route::get('/', [StoreController::class, 'index']);

Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');

Route::post('/store', [ProductController::class, 'store'])->name('products.store');

Route::get('/cart', [StoreController::class, 'cart']); 

Route::get('/aboutUs', [StoreController::class, 'aboutUs']); 

Route::get('/contact', [StoreController::class, 'contact']); 
