@extends('layouts.app')

@section('title', 'About Us - Barista Cafe')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section d-flex justify-content-center align-items-center" style="background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1509042239860-f550ce710b93?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80'); background-size: cover; padding: 100px 0;">
        <div class="container text-center">
            <h1 class="text-white mb-4">{{ $aboutUs['title'] }}</h1>
            <p class="text-white">{{ $aboutUs['description'] }}</p>
        </div>
    </section>

    <!-- About Section -->
    <section class="about-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-12 text-center">
                    <h2>Who We Are</h2>
                    <p>{!! $aboutUs['rawHtml'] !!}</p>
                    <p class="mt-4">
                        &copy; @php echo date('Y'); @endphp Barista Cafe. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    .about-section {
        padding: 80px 0;
        text-align: center;
    }
    .about-section h2 {
        color: #5d4037;
        font-weight: bold;
        margin-bottom: 20px;
    }
    .about-section p {
        color: #333;
        font-size: 1.1rem;
        line-height: 1.6;
    }
</style>
@endsection
