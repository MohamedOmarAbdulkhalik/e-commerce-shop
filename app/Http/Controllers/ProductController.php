<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class ProductController extends Controller
{
    //
    public function index()
    {
        $products = Product::all();
        return view('shop.products', compact('products'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('shop.product-details', compact('product', 'relatedProducts'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('shop.create-product', compact('categories'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id', // تأكد من هذه القاعدة
        'on_sale' => 'boolean',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
    ]);

    // معالجة صورة المنتج إذا تم رفعها
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('products', 'public');
    }

    // إنشاء المنتج مع category_id
    Product::create([
        'name' => $validated['name'],
        'description' => $validated['description'],
        'price' => $validated['price'],
        'category_id' => $validated['category_id'], // تأكد من إضافة هذا
        'on_sale' => $request->has('on_sale'),
        'image_path' => $imagePath
    ]);

    return redirect()->route('products.index')
        ->with('success', 'Product created successfully.');
}

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('shop.edit-product', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // check inputs
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'on_sale' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'remove_image' => 'boolean'
        ]);

        // handel image
        $imagePath = $product->image_path;

        // when user delete image
        if ($request->has('remove_image') && $imagePath) {
            Storage::disk('public')->delete($imagePath);
            $imagePath = null;
        }

        // when a new image is uploaded
        if ($request->hasFile('image')) {
            // delete last image if exist
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // update product
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'price' => $validated['price'],
            'on_sale' => $request->has('on_sale'),
            'image_path' => $imagePath
        ]);

        // redirect with secess message
        return redirect()->route('products.index')
            ->with('success', 'Product "' . $product->name . '" updated successfully!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $productName = $product->name;

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product "' . $productName . '" has been deleted successfully!');
    }
}
