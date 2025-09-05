@extends('layouts.app')

@section('title', 'Products - Barista Cafe')

@section('content')
<main>
    <section class="hero-section d-flex justify-content-center align-items-center" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1445116572660-236099ec97a0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80'); background-size: cover; padding: 100px 0;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-12 mx-auto text-center">
                    <h1 class="text-white mb-4">Our Products</h1>
                    <p class="text-white">Discover our premium selection of coffee products and accessories</p>
                </div>
            </div>
        </div>
    </section>
    {{-- Display Success Messages --}}
    @if(session('success'))
    <div class="container mt-4">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ✅ {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif

    {{-- Display Error Messages --}}
    @if($errors->any())
    <div class="container mt-4">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ❌ There was an error with your request:
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    @endif
    <section class="products-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-12 text-center mb-5">
                    <em>Featured Items</em>
                    <h2>Products Collection</h2>
                </div>

                @unless(count($products) > 0)
                    <div class="col-12 text-center py-5">
                        <div class="no-products">
                            <i class="bi bi-inbox"></i>
                            <h3>No products currently available</h3>
                            <p>Please check back later for new arrivals.</p>
                        </div>
                    </div>
                @else
                    <div class="row">
                        @foreach($products as $product)
                            <div class="col-lg-4 col-md-6 col-12 mb-4">
                                <div class="product-card card h-100 {{ $loop->first ? 'first-product' : '' }}">
                                    <div class="position-relative">
                                        @if($product->on_sale)
                                            <span class="sale-badge">Sale</span>
                                        @endif
                                        <span class="product-number">{{ $loop->index + 1 }}</span>
                                        
                                        <!-- رابط لصفحة التفاصيل -->
                                        <a href="{{ route('products.show', $product->id) }}">
                                            @if($product->image_path)
                                                <!-- عرض الصورة المرفوعة -->
                                                <img src="{{ asset('storage/' . $product->image_path) }}" 
                                                    class="card-img-top" 
                                                    alt="{{ $product->name }}"
                                                    style="height: 250px; object-fit: cover;">
                                            @else
                                                <!-- صورة افتراضية إذا لم توجد صورة -->
                                                <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60" 
                                                    class="card-img-top" 
                                                    alt="{{ $product->name }}"
                                                    style="height: 250px; object-fit: cover;">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                                                {{ $product->name }}
                                            </a>
                                        </h5>
                                        <p class="card-text text-muted">
                                            {{ $product->description ?: 'No description available' }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($product->on_sale)
                                                <div>
                                                    <span class="original-price text-muted text-decoration-line-through me-2">
                                                        ${{ number_format($product->price * 1.2, 2) }}
                                                    </span>
                                                    <span class="sale-price text-danger fw-bold">
                                                        ${{ number_format($product->price, 2) }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="price text-primary fw-bold">
                                                    ${{ number_format($product->price, 2) }}
                                                </span>
                                            @endif
                                            <div class="btn-group">
                                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-outline-primary btn-sm">View</a>
                                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-secondary btn-sm">Edit</a>
                                                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $product->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endunless
            </div>
        </div>
    </section>
    <!-- Delete Confirmation Modals -->
@foreach($products as $product)
<div class="modal fade" id="deleteModal{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel{{ $product->id }}">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete <strong>"{{ $product->name }}"</strong>?</p>
                <p class="text-danger">This action cannot be undone!</p>
                
                @if($product->image_path)
                <div class="alert alert-warning mt-3">
                    <small>⚠️ The product image will also be deleted permanently.</small>
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Permanently</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
</main>


<style>
    .products-section {
        padding: 80px 0;
    }
    .product-card {
        transition: transform 0.3s, box-shadow 0.3s;
        border-radius: 10px;
        overflow: hidden;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
    }
    .first-product {
        border: 3px solid #ffc107;
    }
    .sale-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }
    .new-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #28a745;
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-weight: bold;
    }
    .product-number {
        position: absolute;
        top: 10px;
        left: 10px;
        background-color: #5d4037;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .card-title {
        color: #5d4037;
        font-weight: bold;
    }
    .price {
        font-size: 1.2rem;
        color: #5d4037;
        font-weight: bold;
    }
    .sale-price {
        color: #dc3545;
        font-size: 1.2rem;
        font-weight: bold;
    }
    .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 0.9rem;
        margin-right: 10px;
    }
    .no-products {
        text-align: center;
        padding: 50px 0;
    }
    .no-products i {
        font-size: 5rem;
        color: #ccc;
        margin-bottom: 20px;
    }
</style>
@endsection