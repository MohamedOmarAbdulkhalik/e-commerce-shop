<?php

use App\Exceptions\ProductNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
->withExceptions(function (Exceptions $exceptions) {
    // إرسال بريد إلكتروني عند حدوث ProductNotFoundException
    $exceptions->reportable(function (ProductNotFoundException $e) {
        // تسجيل الخطأ
        Log::error('Product not found exception occurred: ' . $e->getMessage(), [
            'product_id' => $e->getProductId(),
            'exception' => $e,
            'environment' => app()->environment()
        ]);
        
        // إرسال بريد إلكتروني لفريق الدعم (للغرض الاختباري، سنفعله في جميع البيئات)
        try {
            $emailContent = "Critical Error: Product Not Found\n" .
                "Time: " . now()->toDateTimeString() . "\n" .
                "Product ID: " . $e->getProductId() . "\n" .
                "Error Message: " . $e->getMessage() . "\n" .
                "IP Address: " . request()->ip() . "\n" .
                "URL: " . request()->fullUrl() . "\n" .
                "Environment: " . app()->environment();

            Mail::raw($emailContent, function ($message) {
                $message->to('mohamedalaghbari20@gmail.com')
                        ->subject('🚨 Critical Error: Product Not Found - Barista Cafe');
            });
            
            Log::info('Error email sent successfully');
            
        } catch (\Exception $mailException) {
            Log::error('Failed to send error email: ' . $mailException->getMessage());
            Log::error('Mail exception details: ' . $mailException->getTraceAsString());
        }
    });

    // معالجة الاستثناءات العامة
    $exceptions->reportable(function (Throwable $e) {
        Log::error('General exception: ' . $e->getMessage());
    });
})->create();

