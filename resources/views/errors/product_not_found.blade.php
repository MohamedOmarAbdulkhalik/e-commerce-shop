@extends('layouts.app')

@section('title', 'Product Not Found')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-white bg-danger">
                    <h4 class="mb-0">Product Not Found</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-box-open fa-5x text-muted"></i>
                    </div>
                    <h3 class="text-danger mb-3">Oops! Product Not Found</h3>
                    <p class="text-muted mb-4">
                        {{ $message ?? 'The product you are looking for does not exist or has been removed.' }}
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">
                            <i class="fas fa-list me-2"></i>View All Products
                        </a>
                        <a href="{{ url('/') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>Go Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection