<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\AdminController;
use App\Models\User;
// 🛡️ كل المسارات محمية بالـ auth و verified
Route::middleware(['auth', 'verified'])->group(function () {
    
    // الصفحة الرئيسية
    Route::get('/', [StoreController::class, 'index'])->name('home');

    // المنتجات (متاحة لجميع المستخدمين المسجلين)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

    // مسارات التعديل والحذف (محمية بـ Policies)
    Route::get('/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/search', [ProductController::class, 'search'])->name('search');

    // مسارات الادمن مع middleware إضافي للصلاحيات
    Route::middleware(['can:access-admin-panel'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
        Route::get('/add-product', [ProductController::class, 'create'])->name('add-product');
        Route::post('/store', [ProductController::class, 'store'])->name('products.store');
    });

    // المتجر
    Route::get('/cart', [StoreController::class, 'cart'])->name('cart'); 
    Route::get('/about-us', [StoreController::class, 'aboutUs'])->name('about-us'); 
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;
use App\Notifications\NewOrderNotification;

// مسارات اختبار البريد والإشعارات
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/test-welcome-email', function () {
        $user = Auth::user();
        Mail::to($user)->send(new WelcomeEmail($user));
        return 'Welcome email sent!';
    })->name('test.welcome.email');

    Route::get('/test-order-notification', function () {
        $user = Auth::user();
        
        // إنشاء بيانات الطلب
        $orderData = [
            'id' => rand(1000, 9999),
            'total' => rand(50, 500) + (rand(0, 99) / 100),
            'status' => 'pending'
        ];
        
        // إرسال الإشعار
        $user->notify(new NewOrderNotification((object)$orderData));
        
        return 'Order notification sent! Check your database notifications table.';
    })->name('test.order.notification');




});
// مسارات Breeze الأساسية (تسجيل الدخول، تسجيل الحساب، الخروج)
require __DIR__.'/auth.php';