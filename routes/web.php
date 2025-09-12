<?php


use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;

// 🛡️ كل المسارات محمية بالـ auth
Route::middleware(['auth', 'verified'])->group(function () {
    
    // الصفحة الرئيسية
    Route::get('/', [StoreController::class, 'index'])->name('home');

    // المنتجات
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


    // Admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::get('/add-product', [ProductController::class, 'create'])->name('products.create');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
        Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    // المتجر
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart'); 
    Route::get('/aboutUs', [StoreController::class, 'aboutUs'])->name('aboutUs'); 
    Route::get('/contact', [StoreController::class, 'contact'])->name('contact'); 
    Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // الملف الشخصي
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// مسارات Breeze الأساسية (تسجيل الدخول، تسجيل الحساب، الخروج)
require __DIR__.'/auth.php';
