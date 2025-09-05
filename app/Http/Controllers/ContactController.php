<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessage;

class ContactController extends Controller
{
    public function submit(Request $request)
    {
        // التحقق من صحة المدخلات
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:500'
        ], [
            // رسائل الخطأ المخصصة
            'name.required' => 'Please enter your name.',
            'name.min' => 'Your name must be at least 3 characters.',
            'email.required' => 'Please enter your email address.',
            'email.email' => 'Please enter a valid email address.',
            'message.required' => 'Please enter your message.',
            'message.max' => 'Your message cannot exceed 500 characters.'
        ]);

        // هنا يمكنك معالجة البيانات:
        // 1. حفظ الرسالة في قاعدة البيانات
        // 2. إرسال email للإدارة
        // 3. تسجيل الرسالة في النظام
        
        try {
            // مثال: إرسال email للإدارة
            // Mail::to('admin@baristacafe.com')->send(new ContactMessage($validated));
            
            // مثال: حفظ في قاعدة البيانات (إذا كان لديك نموذج Contact)
            // Contact::create($validated);

            // إعادة التوجيه مع رسالة النجاح
            return redirect()->back()->with('success', 'Your message has been sent successfully! We will get back to you soon.');

        } catch (\Exception $e) {
            // في حالة حدوث خطأ
            return redirect()->back()->with('error', 'Sorry, there was an error sending your message. Please try again later.');
        }
    }
}