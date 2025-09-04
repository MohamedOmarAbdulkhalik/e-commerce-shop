<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{

    public function index(){
        return view('shop.index');
    }

    public function products()
    {
        return view('shop.products');
    }

    // تفاصيل منتج واحد
    public function productDetails($id = null)
    {
        // لاحقاً ممكن تمرر بيانات المنتج المحدد
        return view('shop.product-details');
    }

    // صفحة السلة
    public function cart()
    {
        return view('shop.cart');
    }

    // صفحة من نحن
    public function aboutUs()
    {
        return view('shop.about-us');
    }

    // صفحة تواصل معنا
    public function contact()
    {
        return view('shop.contact');
    }
}


