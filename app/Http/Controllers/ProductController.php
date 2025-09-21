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
    public function index()
    {
        $products = Product::all();
        return view('shop.products', compact('products'));
    }

    public function show($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $id)
                ->take(4)
                ->get();

            return view('shop.product-details', compact('product', 'relatedProducts'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Product not found - Show', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user() ? Auth::user()->name : 'Guest',
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

           
            throw new ProductNotFoundException($id, "Product with ID {$id} not found.");
        }
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.create-product', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0', // أضفنا حقل الكمية
                'category_id' => 'required|exists:categories,id',
                'on_sale' => 'boolean',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            // معالجة صورة المنتج إذا تم رفعها
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                
                Log::info('New product image uploaded during creation', [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'image_path' => $imagePath,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            // إنشاء المنتج
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'], // حفظ الكمية
                'category_id' => $validated['category_id'],
                'on_sale' => $request->has('on_sale'),
                'image_path' => $imagePath
            ]);

            // تسجيل إنشاء المنتج
            Log::info('Product created successfully', [
                'admin_user' => Auth::user()->name,
                'admin_id' => Auth::id(),
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity' => $validated['quantity'],
                'timestamp' => now()->toDateTimeString()
            ]);

            // التحقق من الكمية المنخفضة عند الإنشاء
            if ($validated['quantity'] < 5) {
                Log::warning('Low product stock warning - New product', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_quantity' => $validated['quantity'],
                    'admin_user' => Auth::user()->name,
                    'threshold' => 5,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Product creation validation failed', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'validation_errors' => $e->errors(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            throw $e;

        } catch (\Exception $e) {
            Log::error('Product creation failed unexpectedly', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // تسجيل عملية الوصول لصفحة التعديل
            Log::info('Product edit page accessed', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $product->id,
                'product_name' => $product->name,
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return view('shop.edit-product', compact('product'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Product not found for editing', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

           
            throw new ProductNotFoundException($id, "Cannot edit product. Product with ID {$id} not found.");
        }
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
                'quantity' => 'required|integer|min:0', // حقل الكمية مطلوب الآن
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
                if ($imagePath) {
                    Storage::disk('public')->delete($imagePath);
                }
                $imagePath = $request->file('image')->store('products', 'public');
                
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
            $updateData = [
                'name' => $validated['name'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'quantity' => $validated['quantity'], // تحديث الكمية
                'on_sale' => $request->has('on_sale'),
                'image_path' => $imagePath
            ];

            $product->update($updateData);

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
            if ($validated['quantity'] < 5) {
                Log::warning('Low product stock warning', [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'current_quantity' => $validated['quantity'],
                    'admin_user' => Auth::user()->name,
                    'threshold' => 5,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            return redirect()->route('products.index')
                ->with('success', 'Product "' . $product->name . '" updated successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Product not found for update', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

        
            throw new ProductNotFoundException($id, "Cannot update product. Product with ID {$id} not found.");

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Product update validation failed', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'validation_errors' => $e->errors(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            throw $e;

        } catch (\Exception $e) {
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
        try {
            $product = Product::findOrFail($id);
            $productName = $product->name;

            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
                
                Log::info('Product image deleted during product deletion', [
                    'user_id' => Auth::id(),
                    'user_name' => Auth::user()->name,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'timestamp' => now()->toDateTimeString()
                ]);
            }

            $product->delete();

            Log::info('Product deleted successfully', [
                'admin_user' => Auth::user()->name,
                'admin_id' => Auth::id(),
                'product_id' => $id,
                'product_name' => $productName,
                'timestamp' => now()->toDateTimeString()
            ]);

            return redirect()->route('products.index')
                ->with('success', 'Product "' . $productName . '" has been deleted successfully!');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Product not found for deletion', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);


            throw new ProductNotFoundException($id, "Cannot delete product. Product with ID {$id} not found.");

        } catch (\Exception $e) {
            Log::error('Product deletion failed unexpectedly', [
                'user_id' => Auth::id(),
                'user_name' => Auth::user()->name,
                'product_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'ip_address' => request()->ip(),
                'timestamp' => now()->toDateTimeString()
            ]);

            return redirect()->back()
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
}