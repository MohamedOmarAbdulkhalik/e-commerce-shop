<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

// الصفحة الرئيسية
Route::get('/', [StoreController::class, 'index']);

// routes المنتجات - تأكد من أنهم غير معلقين
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
Route::post('/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
});

// routes المتجر
Route::get('/cart', [StoreController::class, 'cart'])->name('cart'); 
Route::get('/aboutUs', [StoreController::class, 'aboutUs'])->name('aboutUs'); 
Route::get('/contact', [StoreController::class, 'contact'])->name('contact'); 
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

// Routes الخاصة بـ Breeze (اتركها كما هي)
// Dashboard (خاص بالمستخدمين المسجلين)
Route::get('/dashboard', function () {
    return view('dashboard'); // اعرض صفحة dashboard.blade.php
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';