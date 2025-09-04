<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
// بيانات وهمية للمنتجات
private $products = [
    [
        'name' => 'Espresso Classic',
        'price' => 12,
        'on_sale' => true,
        'description' => 'Strong espresso coffee with a rich aroma and distinctive flavor.'
    ],
    [
        'name' => 'Cappuccino Deluxe',
        'price' => 15,
        'on_sale' => false,
        'description' => 'A harmonious blend of espresso, steamed milk, and silky milk foam.'
    ],
    [
        'name' => 'Croissant with Almonds',
        'price' => 8,
        'on_sale' => true,
        'description' => 'Fresh croissant filled with almonds and nuts, served warm.'
    ],
    [
        'name' => 'Turkish Coffee',
        'price' => 10,
        'on_sale' => false,
        'description' => 'Authentic Turkish coffee with rich flavor and aroma, served in a traditional pot.'
    ],
    [
        'name' => 'Chocolate Cake',
        'price' => 18,
        'on_sale' => true,
        'description' => 'Rich chocolate cake that melts in your mouth, topped with ganache.'
    ],
    [
        'name' => 'Iced Caramel Macchiato',
        'price' => 16,
        'on_sale' => false,
        'description' => 'A cold espresso drink with milk, caramel, and ice.'
    ],
];

// Dummy data for "About Us" page
private $aboutData = [
    'title' => 'About Barista Cafe',
    'description' => 'We are committed to delivering the best coffee experience with the finest beans and delicious treats.',
    'rawHtml' => '<strong>Trusted by thousands of customers worldwide!</strong>'
];


    public function index(){
        return view('shop.index');
    }

    public function products()
    {
        return view('shop.products', ['products' => $this->products]);
    }

    // تفاصيل منتج واحد
    public function productDetails($id = null)
    {
        $product = $this->products[$id] ?? $this->products[0];

        return view('shop.product-details', ['product' => $product]);
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


