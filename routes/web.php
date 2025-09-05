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

Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');

Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');

Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/cart', [StoreController::class, 'cart']); 

Route::get('/aboutUs', [StoreController::class, 'aboutUs']); 

Route::get('/contact', [StoreController::class, 'contact']); 
