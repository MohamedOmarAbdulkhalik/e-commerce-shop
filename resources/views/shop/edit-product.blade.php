@extends('layouts.app')

@section('title', 'Edit Product - Barista Cafe')

@section('content')
<main>
    <section class="hero-section d-flex justify-content-center align-items-center" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1445116572660-236099ec97a0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80'); background-size: cover; padding: 100px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12 mx-auto text-center">
                    <h1 class="text-white mb-4">Edit Product</h1>
                    <p class="text-white">Update your product information</p>
                </div>
            </div>
        </div>
    </section>

    <section class="edit-product-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-12">
                    <div class="card">
                        <div class="card-header bg-white">
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                                ← Back to Products
                            </a>
                        </div>
                        
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    ✅ {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    ❌ Please fix the following errors:
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Product Name *</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" name="name" value="{{ old('name', $product->name) }}" 
                                                   placeholder="Enter product name" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price ($) *</label>
                                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                                   id="price" name="price" value="{{ old('price', $product->price) }}" 
                                                   placeholder="0.00" min="0" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity *</label>
                                            <input type="number" class="form-control @error('quantity') is-invalid @enderror" 
                                                   id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" 
                                                   placeholder="Enter quantity" min="0" required>
                                            @error('quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Warning: Quantity below 5 will trigger a stock alert</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Sale Status</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="on_sale" name="on_sale" value="1" 
                                                    {{ old('on_sale', $product->on_sale) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="on_sale">
                                                    Mark as on sale
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3" 
                                              placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="image" class="form-label">Product Image</label>
                                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                                   id="image" name="image" accept="image/*">
                                            @error('image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Max file size: 2MB (JPEG, PNG, JPG, GIF)</div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Stock Status</label>
                                            <div class="alert alert-info p-2">
                                                @if($product->quantity == 0)
                                                    <span class="text-danger">❌ Out of Stock</span>
                                                @elseif($product->quantity < 5)
                                                    <span class="text-warning">⚠️ Low Stock ({{ $product->quantity }} remaining)</span>
                                                @else
                                                    <span class="text-success">✅ In Stock ({{ $product->quantity }} available)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if($product->image_path)
                                <div class="mb-3">
                                    <label class="form-label">Current Image</label>
                                    <div>
                                        <img src="{{ asset('storage/' . $product->image_path) }}" 
                                             alt="Current product image" 
                                             class="img-thumbnail" 
                                             style="max-height: 200px;">
                                        <div class="form-check mt-2">
                                            <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                            <label class="form-check-label text-danger" for="remove_image">
                                                Remove current image
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                <div class="mb-3 text-center">
                                    <img id="imagePreview" src="#" alt="Image preview" class="img-thumbnail mt-2" style="display: none; max-height: 200px;">
                                </div>

                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary me-md-2">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Update Product
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .edit-product-section {
        padding: 80px 0;
    }
    
    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
</style>

<script>
    // Image preview functionality
    document.getElementById('image').addEventListener('change', function(e) {
        const preview = document.getElementById('imagePreview');
        const file = e.target.files[0];
        
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display = 'none';
        }
    });

    // Quantity validation with warning
    document.getElementById('quantity').addEventListener('change', function(e) {
        const quantity = parseInt(e.target.value);
        const warningElement = document.getElementById('quantityWarning');
        
        if (quantity < 5) {
            if (!warningElement) {
                const warningDiv = document.createElement('div');
                warningDiv.id = 'quantityWarning';
                warningDiv.className = 'text-warning small mt-1';
                warningDiv.innerHTML = '⚠️ Low stock warning: Quantity is below 5';
                e.target.parentNode.appendChild(warningDiv);
            }
        } else {
            if (warningElement) {
                warningElement.remove();
            }
        }
    });

    // Check initial quantity on page load
    document.addEventListener('DOMContentLoaded', function() {
        const initialQuantity = parseInt(document.getElementById('quantity').value);
        if (initialQuantity < 5) {
            const warningDiv = document.createElement('div');
            warningDiv.id = 'quantityWarning';
            warningDiv.className = 'text-warning small mt-1';
            warningDiv.innerHTML = '⚠️ Low stock warning: Quantity is below 5';
            document.getElementById('quantity').parentNode.appendChild(warningDiv);
        }
    });
</script>
@endsection