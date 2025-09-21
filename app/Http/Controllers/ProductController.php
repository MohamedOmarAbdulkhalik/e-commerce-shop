<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Exceptions\ProductNotFoundException;


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
        return view('admin.create-product', compact('categories'));
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
        try {
            $product = Product::findOrFail($id);
            $oldData = $product->toArray();

            // check inputs
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'on_sale' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'remove_image' => 'boolean',
                'quantity' => 'sometimes|integer|min:0' // أضف هذا الحقل إذا كان موجوداً
            ]);

            // handel image
            $imagePath = $product->image_path;

            // when user delete image
            if ($request->has('remove_image') && $imagePath) {
                Storage::disk('public')->delete($imagePath);
                $imagePath = null;
                
                // تسجيل حذف الصورة
                Log::info('Product image removed', [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            // when a new image is uploaded
            if ($request->hasFile('image')) {
                // delete last image if exist
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('products', 'public');
                
                // تسجيل رفع صورة جديدة
                Log::info('New product image uploaded', [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'image_path' => $imagePath,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            // update product
            $product->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'on_sale' => $request->has('on_sale'),
                'image_path' => $imagePath,
                'quantity' => $validated['quantity'] ?? $product->quantity // تحديث الكمية إذا كانت موجودة
            ]);

            // 1. تسجيل عملية التعديل
            Log::info('Product updated by admin', [
                'admin_user' => Auth::user()->name,
                'admin_id' => Auth::id(),
                'product_id' => $product->id,
                'product_name' => $product->name,
                'old_data' => $oldData,
                'new_data' => $product->fresh()->toArray(),
                'ip_address' => $request->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // 2. التحقق من الكمية وإضافة تحذير إذا كانت منخفضة
            if (isset($validated['quantity']) && $validated['quantity'] < 5) {
                Log::warning('Low product stock warning', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_quantity' => $validated['quantity'],
                    'admin_user' => Auth::user()->name,
                    'threshold' => 5,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            // redirect with success message
            return redirect()->route('products.index')
                ->with('success', 'Product "' . $product->name . '" updated successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // تسجيل الخطأ
            Log::error('Product not found for update', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            // إطلاق الاستثناء المخصص
            throw new ProductNotFoundException($id, "Cannot update product. Product with ID {$id} not found.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            // تسجيل أخطاء التحقق
            Log::warning('Product update validation failed', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'validation_errors' => $e->errors(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            throw $e; // إعادة إطلاق الاستثناء للتعامل معه بشكل طبيعي

        } catch (\Exception $e) {
            // تسجيل أي خطأ غير متوقع
            Log::error('Product update failed unexpectedly', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
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
