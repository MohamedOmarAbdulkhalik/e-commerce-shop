<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;

Route::get('/', function () {
    return view('shop.index');
});

Route::get('/shop', [ShopController::class, 'index']);


