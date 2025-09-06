@extends('layouts.app')

@section('title', 'Product Details - Barista Cafe')

@section('content')
<main>
    <section class="hero-section d-flex justify-content-center align-items-center" 
        style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), 
        url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-1.2.1&auto=format&fit=crop&w=1351&q=80'); 
        background-size: cover; padding: 100px 0;">
        <div class="container text-center">
            <h1 class="text-white mb-4">{{ $product->name }}</h1>
            <p class="text-white">Discover more about this product</p>
        </div>
    </section>

    <section class="product-details-section section-padding">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-lg-6 col-md-6 col-12 mb-4">
                    <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                        alt="{{ $product->name }}" class="img-fluid rounded shadow">
                </div>
                <div class="col-lg-6 col-md-6 col-12">
                    <h2 class="mb-3">{{ $product->name }}</h2>
                    
                    <!-- عرض التصنيف -->
                    @if($product->category)
                    <div class="mb-2">
                        <span class="badge bg-secondary">
                            {{ $product->category->name }}
                        </span>
                    </div>
                    @endif
                    
                    <p class="mb-4">{{ $product->description }}</p>
                    
                    @if($product->on_sale)
                        <div class="mb-4">
                            <span class="original-price">${{ number_format($product->price * 1.2, 2) }}</span>
                            <span class="sale-price">${{ number_format($product->price, 2) }}</span>
                            <span class="badge bg-danger ms-2">On Sale</span>
                        </div>
                    @else
                        <p class="price mb-4">${{ number_format($product->price, 2) }}</p>
                        <span class="badge bg-success">New</span>
                    @endif

                    <button class="btn btn-primary btn-lg">Add to Cart</button>
                </div>
            </div>
        </div>
    </section>

    <!-- قسم المنتجات ذات الصلة -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
    <section class="related-products-section section-padding bg-light">
        <div class="container">
            <h3 class="text-center mb-5">Related Products</h3>
            <div class="row">
                @foreach($relatedProducts as $relatedProduct)
                <div class="col-lg-3 col-md-6 col-12 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" 
                            class="card-img-top" alt="{{ $relatedProduct->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $relatedProduct->name }}</h5>
                            @if($relatedProduct->category)
                            <span class="badge bg-secondary mb-2">
                                {{ $relatedProduct->category->name }}
                            </span>
                            @endif
                            <p class="card-text">${{ number_format($relatedProduct->price, 2) }}</p>
                            <a href="{{ route('products.show', $relatedProduct->id) }}" class="btn btn-outline-primary">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
</main>

<style>
    .product-details-section {
        padding: 80px 0;
    }
    .related-products-section {
        padding: 60px 0;
    }
    .price {
        font-size: 1.5rem;
        color: #5d4037;
        font-weight: bold;
    }
    .sale-price {
        color: #dc3545;
        font-size: 1.5rem;
        font-weight: bold;
    }
    .original-price {
        text-decoration: line-through;
        color: #6c757d;
        font-size: 1rem;
        margin-right: 10px;
    }
    .card {
        transition: transform 0.3s ease;
    }
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endsection